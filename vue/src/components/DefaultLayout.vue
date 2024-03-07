<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <!--
    This example requires updating your template:

    ```
    <html class="h-full bg-gray-100">
    <body class="h-full">
    ```
  -->
  <div class="min-h-full">
    <Disclosure as="nav" class="bg-gray-800" v-slot="{ open }">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <img
                class="h-8 w-8"
                src="../assets/logo.png"
                alt="Workflow"
              />
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <router-link
                  v-for="item in navigation"
                  :key="item.name"
                  :to="item.to"
                  active-class="nav-link-active"
                  :class="[
                    this.$route.name === item.to.name
                      ? ''
                      : 'nav-link-hover',
                    'px-3 py-2 rounded-md text-sm font-medium',
                  ]"
                  >{{ item.name }}
                </router-link>
              </div>
            </div>
          </div>

          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">

              <!-- Profile dropdown -->
              <Menu v-if="isAuthenticated" as="div" class="ml-3 relative">
                <div class="flex">
                  <MenuButton
                    class="max-w-xs bg-gray-800 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                  >
                    <span class="sr-only">Open user menu</span>

                    <div class="mx-3">
                      <div
                        class="text-left user-name"
                      >
                        {{ user.name }}
                      </div>
                      <div
                        class="user-email"
                      >
                        {{ user.email }}
                      </div>
                    </div>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-8 w-8"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="white"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                      />
                    </svg>
                  </MenuButton>
                </div>
                <transition
                  enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <MenuItems
                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                  >
                    <MenuItem v-slot="{}">
                      <a
                        @click="logout"
                        :class="[
                          'block px-4 py-2 text-sm text-gray-700 cursor-pointer',
                        ]"
                        >Sign out</a
                      >
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>

              <!-- "Sign in with Google" button, shown if not authenticated -->
              <button v-else
                @click="console.log('Sign in with Google')"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center shadow-lg"
              >
                <svg class="w-6 h-6 mr-2" aria-hidden="true" focusable="false" fill="currentColor" viewBox="0 0 48 48">
                  <path fill="#FFC107" d="M43.6 20H24v8h11.8C34.7 36.3 29.8 40 24 40c-8.4 0-15.6-6.8-16-15.2h-8v-6c0-8.8 7.2-16 16-16 4.4 0 8.4 1.8 11.3 4.7l5.7-5.7C34.6 4.1 29.6 2 24 2 10.8 2 0 12.8 0 26s10.8 24 24 24c13.3 0 24-10.7 24-24 0-1.3-.2-2.7-.4-4z"/>
                  <path fill="#FF3D00" d="M6.3 29.8C5.5 27.9 5 25.8 5 23.8s.5-4.1 1.3-6V11h8c1.1 4.5 4.5 8.2 9.7 9.8v9.2H15.6c-1.5 0-2.9-.4-4.3-1.2z"/>
                  <path fill="#4CAF50" d="M24 42c4.5 0 8.5-1.6 11.6-4.2l-5.4-5.4c-1.6 1.1-3.6 1.8-6.2 1.8-5.8 0-10.7-3.7-12.4-8.8H6.2v6C10.4 38.2 16.6 42 24 42z"/>
                  <path fill="#1976D2" d="M43.6 20H24v8h11.8C35.9 28 34.7 30 33.2 32l5.4 5.4C41.5 34.4 43.6 30 43.6 26c0-1.3-.2-2.6-.4-4z"/>
                </svg>
                Sign in with Google
              </button>
            </div>
          </div>

          <div class="-mr-2 flex md:hidden">
            <!-- Mobile menu button -->
            <DisclosureButton
              class="bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
            >
              <span class="sr-only">Open main menu</span>
              <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
              <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
            </DisclosureButton>
          </div>
        </div>
      </div>

      <DisclosurePanel class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <router-link
            v-for="item in navigation"
            :key="item.name"
            :to="item.to"
            active-class="nav-link-active"
            :class="[
              this.$route.name === item.to.name
                ? ''
                : 'nav-link-hover',
              'block px-3 py-2 rounded-md text-base font-medium',
            ]"
            >{{ item.name }}
          </router-link>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-700">
          <div class="flex items-center px-5">
            <div class="flex-shrink-0">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8"
                fill="none"
                viewBox="0 0 24 24"
                stroke="white"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
            <div class="ml-3">
              <div class="user-name">
                {{ user.name }}
              </div>
              <div class="user-email">
                {{ user.email }}
              </div>
            </div>
          </div>
          <div class="mt-3 px-2 space-y-1">
            <DisclosureButton
              as="a"
              @click="logout"
              class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 cursor-pointer"
              >Sign out
            </DisclosureButton>
          </div>
        </div>
      </DisclosurePanel>
    </Disclosure>

    <router-view :key="$route.path"></router-view>

    <Notification />
  </div>
</template>

<script>
import {
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
} from "@headlessui/vue";
import { BellIcon, MenuIcon, XIcon } from "@heroicons/vue/outline";
import { useStore } from "vuex";
import { computed } from "vue";
import { useRouter } from "vue-router";
import Notification from "./Notification.vue";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    BellIcon,
    MenuIcon,
    XIcon,
    Notification,
  },
  setup() {
    const store = useStore();
    const router = useRouter();
    const isAuthenticated = computed(() => store.getters.isAuthenticated);
    const navigation = computed(() => {
      const baseNavigation = [
        { name: "Dashboard", to: { name: "MatricesDashboard" } },
        { name: "Public Matrices", to: { name: "MatricesPublic" } },
      ];

      if (isAuthenticated.value) {
        baseNavigation.push({ name: "My Matrices", to: { name: "Matrices" } });
      }

      return baseNavigation;
    });
        
    function logout() {
      store.dispatch("logout").then(() => {
        router.push({
          name: "MatricesDashboard",
        });
      });
    }

    store.dispatch("getUser");

    return {
      user: computed(() => store.state.user.data),
      navigation,
      logout,
      isAuthenticated,
    };
  },
};
</script>
