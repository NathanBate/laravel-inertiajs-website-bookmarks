<script setup>
import {ref} from 'vue';
import BreezeApplicationLogo from '@/Components/BreezeComponents/ApplicationLogo.vue';
import BreezeDropdown from '@/Components/BreezeComponents/Dropdown.vue';
import BreezeDropdownLink from '@/Components/BreezeComponents/DropdownLink.vue';
import BreezeNavLink from '@/Components/BreezeComponents/NavLink.vue';
import BreezeResponsiveNavLink from '@/Components/BreezeComponents/ResponsiveNavLink.vue';
import {Link} from '@inertiajs/inertia-vue3'
import HomeIcon from '../Icons/Home'
import NextIcon from '../Icons/Next'
import FlashMessages from '@/Components/InertiaComponents/FlashMessages';

const showingNavigationDropdown = ref(false);
</script>

<template>
	<div>
		<div class="min-h-screen bg-gray-100">
			<nav class="bg-white border-b border-gray-100">

				<!-- Primary Navigation Menu -->
				<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
					<div class="flex justify-between h-16">
						<div class="flex">
							<!-- Logo -->
							<div class="shrink-0 flex items-center my-auto">
								<Link :href="route('dashboard')">
									<BreezeApplicationLogo class="block w-8 h-auto text-gray-400"/>
								</Link>
							</div>

							<!-- Navigation Links -->
							<div class="hidden sm:-my-px sm:ml-4 sm:flex">
								<div class="my-auto font-bold text-gray-400">Website Bookmarks</div>
							</div>
						</div>

						<div class="hidden sm:flex sm:items-center sm:ml-6">

							<!-- Settings Dropdown -->
							<div class="ml-3 relative">
								<BreezeDropdown align="right" width="48">
									<template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{
		                                            $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name
	                                            }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
									</template>

									<!-- Settings Dropdown Options -->
									<template #content>
	                  <BreezeDropdownLink :href="'/profile/' + $page.props.auth.user.id + '/edit'">Your Profile</BreezeDropdownLink>
										<BreezeDropdownLink
												v-if="(($page.props.auth.user.role == 'Super') || ($page.props.auth.user.role == 'Admin'))"
												href="/users">
											Manage Users
										</BreezeDropdownLink>
										<BreezeDropdownLink :href="route('logout')" method="post" as="button">
											Log Out
										</BreezeDropdownLink>
									</template>
								</BreezeDropdown>
							</div>
						</div>

						<!-- Hamburger -->
						<div class="-mr-2 flex items-center sm:hidden">
							<button @click="showingNavigationDropdown = ! showingNavigationDropdown"
							        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
								<svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
									<path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
									      stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
									<path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
									      stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
								</svg>
							</button>
						</div>
					</div>
				</div>

				<!-- Responsive Navigation Menu -->
				<div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">

					<!-- Responsive Settings Options -->
					<div class="pt-4 pb-1 border-t border-gray-200">
						<div class="px-4">
							<div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
							<div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
						</div>

						<div class="mt-3 space-y-1">
							<BreezeResponsiveNavLink :href="route('logout')" method="post" as="button">
								Log Out
							</BreezeResponsiveNavLink>
						</div>
					</div>
				</div>
			</nav>


			<!-- Page Content Wrapper -->
			<div class=" sm:max-w-xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl mx-auto py-2 px-4 sm:px-6 lg:px-8">

				<div class="my-4">
					<slot name="BreadCrumbs"/>
				</div>

				<slot name="flashMessages">
					<FlashMessages/>
				</slot>

				<slot/>

			</div>
		</div>
	</div>
</template>
