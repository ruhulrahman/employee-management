<template>
  <div class="sidebar-wrapper">
    <a href="javascript:" class="logo-route">
      <div class="logo-wrapper">
        <!-- <img src="../assets/images/book.png" class="logo-img" alt=""> -->
        <h2 class="title">Leave Management</h2>
      </div>
    </a>
    <div class="sidebar-navigation">
      <CollapseMenu :items="items" :open="true" :horizontal="horizontal" :sidebarGroupTitle="sidebarGroupTitle" />
      <ul>
        <li>
          <router-link to="/dashboard">
            <a href="">
              <div>
                <svg id="Layer_1" data-name="Layer 1" viewBox="0 0 100 100" style="fill:#fff;">
                  <path class="cls-1"
                    d="M50,21.25l25,22.5V82.8H65v-30H35v30H25v-39l25-22.5M50,7.8,2.87,50.21a1.49,1.49,0,0,0,1,2.59H15v40H45v-30H55v30H85v-40H96.13a1.49,1.49,0,0,0,1-2.59Z" />
                </svg>
              </div>
              Dashboard
            </a>
          </router-link>
        </li>

        <li v-if="has_permission('permitted')" class="submenu" style="margin-left: 10px;">
          <a href="#" v-b-toggle.user_management_menu>
            <i class="ri-folder-user-fill pr-2"></i>
            Leave Management
            <i class="ri-arrow-down-s-line cus-arrow"></i>
          </a>
          <b-collapse id="user_management_menu">
            <ul>
              <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                <router-link to="/leave-request">
                  <p>Leave Request</p>
                </router-link>
              </li>
              <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                <router-link to="/user">
                  <p>User</p>
                </router-link>
              </li>
            </ul>
          </b-collapse>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import CollapseMenu from './menus/CollapseMenu.vue'
export default {
  Name: 'Sidebar',
  components: {
    CollapseMenu
  },
  mounted() {
  },
  data() {
    return {
      items: [],
      homeURL: { type: Object, default: () => ({ path: '/auth/dashboard' }) },
      logo: { type: String, default: require('../assets/images/logo.png') },
      horizontal: true,
      toggleButton: true,
      logoShow: true,
      onlyLogo: true,
      onlyLogoText: true,
      sidebarGroupTitle: true
    }
  }
}
</script>
<style>
/* Your custom styles here */
.sub-menu {
  display: none;
  list-style: none;
}

.submenu:hover .sub-menu {
  display: block;
}
</style>
