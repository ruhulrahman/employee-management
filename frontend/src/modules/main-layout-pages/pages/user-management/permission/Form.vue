<template>
  <b-overlay :show="loading">
    <div class="formBoder">
      <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
        <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
          <b-col v-if="!form.id" lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="Start Date" vid="start_date" v-slot="{ errors }">
              <b-form-group
                id="start_date"
                label="start_date"
                label-for="start_date"
              >
              <template v-slot:label>
                Start Date
              </template>
                <b-form-input
                  type="date"
                  id="start_date"
                  v-model="form.start_date"
                  placeholder="Enter start date"
                  :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
                <div class="invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col v-if="!form.id" lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="End Date" vid="end_date" v-slot="{ errors }">
              <b-form-group
                id="end_date"
                label="end_date"
                label-for="end_date"
              >
              <template v-slot:label>
                End Date
              </template>
                <b-form-input
                  type="date"
                  id="end_date"
                  v-model="form.end_date"
                  placeholder="Enter end date"
                  :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
                <div class="invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col v-if="!form.id" lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="Reason" vid="reason" v-slot="{ errors }">
              <b-form-group
                id="reason"
                label="reason"
                label-for="reason"
              >
              <template v-slot:label>
                Reason
              </template>
                <b-form-input
                  id="reason"
                  v-model="form.reason"
                  placeholder="Enter leave reason"
                  :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
                <div class="invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="status" vid="status">
              <b-form-group
                label-for="status"
                slot-scope="{ valid, errors }"
              >
              <template v-slot:label>
                Status
              </template>
              <b-form-select
                plain
                v-model="form.status"
                :options="statusList"
                id="status"
                :state="errors[0] ? false : (valid ? true : null)"
              >
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
  data () {
    return {
      saveBtn: this.editItem ? 'Update' : 'Save',
      form: {
        start_date: '',
        end_date: null,
        reason: '',
        status: ''
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
  created () {
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
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/leave-request/add', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/leave-request/update', this.form)
      }
      if (result.success) {
        this.$emit('loadList', true)
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$bvModal.hide('modal-1')
        this.loading = false
      } else {
        this.$refs.form.setErrors(result.errors)
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
