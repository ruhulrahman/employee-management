<template>
  <div class="section-wrapper">
    <b-breadcrumb>
      <b-breadcrumb-item to="/dashboard">
        <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
        Dashboard
      </b-breadcrumb-item>
      <b-breadcrumb-item active>Leave Management</b-breadcrumb-item>
    </b-breadcrumb>
  <b-card class="mt-3">
    <b-card-title>
        <b-row>
          <b-col>
            <h4 class="card-title mb-0 pl-0">Leave List</h4>
          </b-col>
          <b-col class="text-right">
            <b-button size="sm" variant="primary" @click="openAddNewModal()"><i class="ri-add-fill"></i>Add New Leave</b-button>
          </b-col>
        </b-row>
      </b-card-title>
      <b-row>
        <b-col>
          <b-overlay :show="loading">
            <b-card>
              <div class="table-wrapper table-responsive">
                <table class="table table-striped table-hover table-bordered">
                  <thead>
                    <tr style="font-size: 12px;">
                      <th scope="col" class="text-center">SL</th>
                      <th scope="col" class="text-center">Employee Name</th>
                      <th scope="col" class="text-center">Start Date</th>
                      <th scope="col" class="text-center">End Date</th>
                      <th scope="col" class="text-center">Reason</th>
                      <th scope="col" class="text-center">Status</th>
                    </tr>
                  </thead>
                  <template v-if="listData.length">
                    <tbody v-for="(item, index) in listData" :key="index">
                      <tr style="font-size: 12px;">
                        <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                        <td class="text-left">{{ item.employee?.name }}</td>
                        <td class="text-left">{{ dDate(item.start_date) }}</td>
                        <td class="text-left">{{ dDate(item.end_date) }}</td>
                        <td class="text-left">{{ item.reason }}</td>
                        <td class="text-left">{{ item.status }}</td>
                        <td class="text-center">
                          <a v-tooltip="'Edit'" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit" @click="editData(item)"><i class="ri-pencil-fill"></i></a>
                          <!-- <a v-tooltip="'Delete'" @click="deleteConfirmation(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a> -->
                        </td>
                      </tr>
                    </tbody>
                  </template>
                  <template v-else>
                      <tr>
                        <td colspan="12" class="notFound">Data Not Found</td>
                      </tr>
                  </template>
                </table>
              </div>
            </b-card>
          </b-overlay>
        </b-col>
     </b-row>
  </b-card>
    <b-modal id="modal-1" ref="editModal" size="md" title="Permission Form" :hide-footer="true">
      <Form @loadList="loadData" :editItem="editItem"/>
    </b-modal>
    <!-- pagination -->
    <div class="pagination-wrapper mt-4">
      <span>Showing {{ pagination.slOffset }} from {{ pagination.totalRows }} entries</span>
      <b-pagination
        size="sm"
        v-model="pagination.currentPage"
        :per-page="pagination.perPage"
        :total-rows="pagination.totalRows"
        @input="searchData"
        />
    </div>
  </div>
</template>

<script>
import Form from './Form.vue'
import RestApi, { baseURL } from '@/config'
// import { permissionList } from '../../../api/routes'
export default {
  components: {
    Form
  },

  data () {
    return {
      // pagination
      rows: 20,
      currentPage: 1,
      // form data
      search: {
        name: '',
        code: '',
        type: null
      },
      value: '',
      listData: [],
      loading: false,
      editItem: '',
      statusList: [
        { value: 'Pending', text: 'Pending' },
        { value: 'Approved', text: 'Approved' },
        { value: 'Rejected', text: 'Rejected' }
      ]
    }
  },
  watch: {
    // 'search.name': function (oldValue, newValue) {
    //   this.searchData()
    // },
    // 'search.code': function (oldValue, newValue) {
    //   this.searchData()
    // },
    // 'search.type': function (oldValue, newValue) {
    //   this.searchData()
    // }
  },
  created () {
    this.loadData()
  },
  computed: {
  },
  methods: {
    openAddNewModal () {
      this.editItem = ''
      this.$refs.editModal.show()
    },
    editData (item) {
      this.editItem = JSON.stringify(item)
      this.$refs.editModal.show()
    },
    searchData () {
      this.loadData()
    },
    clearData () {
      this.search = {
        name: '',
        code: '',
        type: ''
      }
      this.loadData()
    },
    async loadData () {
      this.loading = true
      const params = Object.assign({}, this.search, { page: this.pagination.currentPage, per_page: this.pagination.perPage })
      var result = await RestApi.getData(baseURL, 'api/leave-request/getLoggedInUserLeaveRequest', params)
      if (result.success) {
        this.listData = result.data.data
        this.paginationData(result.data)
      }
      this.loading = false
    },
    getParentName (id) {
      const Obj = this.parentList.find(el => el.value === id)
      if (Obj !== undefined) {
        return Obj.text
      } else {
        return ''
      }
    },
    deleteConfirmation (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          // declare confirmed method to hit api
          this.deletePermission(item)
        }
      })
    },
    async deletePermission (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_permission_data', item)
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.loadData()
      }
      this.loading = false
    }
  }
}
</script>
<style>
 .notFound {
   text-align: center;
 }
</style>
