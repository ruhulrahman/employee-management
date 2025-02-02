<template>
  <b-overlay :show="loading">
    <div class="formBoder">
      <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
        <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
          <b-row>
            <b-col lg="12" md="12" sm="12" xs="12">
              <ValidationProvider name="status" vid="status">
                <b-form-group label-for="status" slot-scope="{ valid, errors }">
                  <template v-slot:label>
                    Status
                  </template>
                  <b-form-select plain v-model="form.status" :options="statusList" id="status"
                    :state="errors[0] ? false : (valid ? true : null)">
                    <template v-slot:first>
                      <b-form-select-option :value=null>Select Status</b-form-select-option>
                    </template>
                  </b-form-select>
                  <div class="invalid-feedback">
                    {{ errors[0] }}
                  </div>
                </b-form-group>
              </ValidationProvider>
            </b-col>
          </b-row>
          <div class="row mt-3">
            <div class="col-sm-3"></div>
            <div class="col text-right">
              <b-button type="submit" variant="primary" class="mr-2">{{ saveBtn }}</b-button>
              &nbsp;
              <b-button variant="danger" class="mr-1" @click="$bvModal.hide('modal-1')">Cancel</b-button>
            </div>
          </div>
        </b-form>
      </ValidationObserver>
    </div>
  </b-overlay>
</template>
<script>
import RestApi, { baseURL } from '@/config'
// import { permissionStore } from '../../../api/routes'

export default {
  props: ['editItem'],
  data() {
    return {
      saveBtn: this.editItem ? 'Update' : 'Save',
      form: {
        start_date: '',
        end_date: '',
        reason: '',
        status: null
      },
      errors: [],
      valid: null,
      loading: false,
      statusList: [
        { value: 'Approved', text: 'Approved' },
        { value: 'Rejected', text: 'Rejected' }
      ]
    }
  },
  created() {
    if (this.editItem) {
      this.form = JSON.parse(this.editItem)
    }
  },
  computed: {
    typeList: function () {
      const list = [
        { value: 'Page', text: 'Page' },
        { value: 'Feature', text: 'Feature' }
      ]
      return list
    }
  },
  watch: {
    'form.name': function (newVal) {
      const nameLowerCase = newVal.toLowerCase()
      const permisCode = nameLowerCase.replaceAll(' ', '_')
      this.form.code = permisCode
    }
  },
  methods: {

    async submitData() {
      this.loading = true
      let result = ''
      try {
        result = await RestApi.postData(baseURL, 'api/leave-request/ApproveLeaveRequest', this.form)
        this.$emit('loadList', true)
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$bvModal.hide('modal-1')
        this.loading = false
      } catch (error) {
        this.$refs.form.setErrors(error)
      } finally {
        this.loading = false
      }

    }
  }
}
</script>
<style>
.formBoder {
  border: 1px solid;
  margin: 5px;
  padding: 35px;
  font-size: 13px
}
</style>
