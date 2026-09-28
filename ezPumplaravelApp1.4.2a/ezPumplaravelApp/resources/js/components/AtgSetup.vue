<template>
    <div>

        <h3 class="mb-4">ATG Setup Panel</h3>

        <!-- ================= TAB NAV ================= -->

        <ul class="nav nav-tabs mb-4">

            <li class="nav-item">
                <a class="nav-link"
                   :class="{ active: activeTab === 'products' }"
                   @click="activeTab='products'">
                    Products
                </a>
            </li>



            <li class="nav-item">
                <a class="nav-link"
                   :class="{ active: activeTab === 'tanks' }"
                   @click="activeTab='tanks'">
                    Tanks
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                   :class="{ active: activeTab === 'alarms' }"
                   @click="activeTab='alarms'">
                    Alarms
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   :class="{ active: activeTab === 'Shift' }"
                   @click="activeTab='Shift'">
                    Shift
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   :class="{ active: activeTab === 'vendors' }"
                   @click="activeTab='vendors'">
                    Vendors
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   :class="{ active: activeTab === 'emails' }"
                   @click="activeTab='emails'">
                    Email Settings
                </a>
            </li>
        </ul>

        <!-- ================================================= -->
        <!-- ================= PRODUCTS ====================== -->
        <!-- ================================================= -->

        <div v-if="activeTab==='products'">

            <button class="btn btn-primary mb-3"
                    @click="openProductModal">
                + Add Product
            </button>

            <table class="table table-bordered">

                <thead>
                <tr>
                    <th>Name</th>
                    <th>UOM</th>
                    <th>Rate</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>

                <tr v-for="p in products" :key="p.ICODE">

                    <td>{{ p.ITMNAME }}</td>
                    <td>{{ p.UOM }}</td>
                    <td>{{ p.PRATE/100 }}</td>

                    <td>

                        <button class="btn btn-warning btn-sm"
                                @click="openEditProductModal(p)">
                            Edit
                        </button>

                    </td>

                </tr>

                </tbody>

            </table>

        </div>

        <!-- ================= PRODUCT MODAL ================= -->

        <div v-if="showProductModal"
             class="modal fade show d-block"
             style="background:rgba(0,0,0,0.6)">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>{{ productForm.id ? 'Edit Product' : 'Add Product' }}</h5>

                        <button class="btn-close"
                                @click="closeProductModal"></button>
                    </div>

                    <div class="modal-body">

                        <input v-model="productForm.ITMNAME"
                               placeholder="Item Name"
                               class="form-control mb-2">

                        <input v-model="productForm.UOM"
                               placeholder="UOM"
                               class="form-control mb-2">

                        <input v-model="productForm.PRATE"
                               type="number"
                               placeholder="product Rate"
                               class="form-control mb-2">


                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-secondary"
                                @click="closeProductModal">
                            Cancel
                        </button>

                        <button class="btn btn-success"
                                @click="saveProduct">
                            Save
                        </button>

                    </div>

                </div>
            </div>
        </div>
        <div v-if="showEditProductModal"   class="modal fade show d-block"
             style="background:rgba(0,0,0,0.6)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit Product</h5>
                        <button class="btn-close" @click="closeEditProductModal"></button>
                    </div>
                    <div class="modal-body">
                        <input v-model="editProductForm.ITMNAME" placeholder="Item Name" class="form-control mb-2">
                        <input v-model="editProductForm.UOM" placeholder="UOM" class="form-control mb-2">
                        <input v-model="editProductForm.PRATE" type="number" placeholder="Purchase Rate" class="form-control mb-2">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="closeEditProductModal">Cancel</button>
                        <button class="btn btn-success" @click="saveEditProduct">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ================================================= -->
        <!-- ================= VENDORS ======================= -->
        <!-- ================================================= -->

        <div v-if="activeTab==='vendors'">

            <!-- Add Vendor Button -->
            <div class="mb-3 text-end">
                <button class="btn btn-primary" @click="openVendorModal">
                    + Add Vendor
                </button>
            </div>

            <!-- Vendor Table -->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>CNIC</th>
                    <th width="150">Action</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="v in vendors" :key="v.id">
                    <td>{{ v.VNAME }}</td>
                    <td>{{ v.PH1 }}</td>
                    <td>{{ v.CNIC }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            <button class="btn btn-warning btn-sm"
                                    @click="editVendor(v)">
                                Edit
                            </button>

                            <button class="btn btn-danger btn-sm"
                                    @click="deleteVendor(v.id)">
                                Delete
                            </button>

                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- Modal -->
            <div v-if="showVendorModal"
                 class="modal fade show d-block"
                 style="background:rgba(0,0,0,0.6)">

                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5>
                                {{ vendorForm.id ? 'Edit Vendor' : 'Add Vendor' }}
                            </h5>
                            <button class="btn-close"
                                    @click="closeVendorModal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label>Name *</label>
                                <input type="text"
                                       class="form-control"
                                       v-model="vendorForm.VNAME">
                            </div>

                            <div class="mb-3">
                                <label>Phone</label>
                                <input type="text"
                                       class="form-control"
                                       v-model="vendorForm.PH1">
                            </div>

                            <div class="mb-3">
                                <label>CNIC</label>
                                <input type="text"
                                       class="form-control"
                                       v-model="vendorForm.CNIC">
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary"
                                    @click="closeVendorModal">
                                Cancel
                            </button>
                            <button class="btn btn-success"
                                    @click="saveVendor">
                                Save
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Delete Confirmation Modal -->
            <div v-if="showDeleteModal"
                 class="modal fade show d-block"
                 style="background:rgba(0,0,0,0.6)">

                <div class="modal-dialog modal-sm">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h6>Confirm Delete</h6>
                            <button class="btn-close"
                                    @click="showDeleteModal=false"></button>
                        </div>

                        <div class="modal-body text-center">
                            Are you sure you want to delete this vendor?
                        </div>

                        <div class="modal-footer justify-content-center">

                            <button class="btn btn-secondary btn-sm"
                                    @click="showDeleteModal=false">
                                Cancel
                            </button>

                            <button class="btn btn-danger btn-sm"
                                    @click="deleteVendor">
                                Yes Delete
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>



        <!-- ================================================= -->
        <!-- ================= TANKS ========================= -->
        <!-- ================================================= -->
<!--        <div v-if="activeTab === 'Shift'">-->
        <form v-if="activeTab === 'Shift'" @submit.prevent="updateShift('shift')" class="card shadow-sm p-4">
            <div class="mb-3">
                <label class="form-label fw-bold">Select Shift</label>
                <select v-model="shift.selected" class="form-select w-25">
                    <option disabled value="">-- Select Shift --</option>
                    <option value="1">No of Shifts 1 (24Hours)</option>
                    <option value="2">No of Shifts 2 (12Hours)</option>
                    <option value="3">No of Shifts 3(8Hours)</option>
                </select>
            </div>
            <!--            <button class="btn btn-primary w-25">⏱ Update Shift</button>-->
            <button class="btn custom-btn btn-shift">⏱ Update Shift</button>
        </form>
<!--        </div>-->
        <!-- ================= TANKS ================= -->

        <div v-if="activeTab==='tanks'">

            <a href="/tank/add-tank" class="btn btn-primary mb-2" v-if="canAddTank" style="float:right">
                <svg
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g
                        id="SVGRepo_tracerCarrier"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M4 12H20M12 4V20"
                            stroke="#ffffff"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                    </g>
                </svg> Add Tank
            </a>&nbsp;&nbsp;&nbsp;<br>

            <table class="table table-bordered">

                <thead class="table-dark">
                <tr>
                    <th>Tank Name</th>
                    <th>Fuel Type</th>
                    <th>Capacity</th>
                    <th>Temp</th>
                    <th>Water Height</th>
                    <th>Station</th>
                    <th>Product</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>

                <tr v-for="t in tanks" :key="t.id">

                    <td>{{ t.tank_name }}</td>
                    <td>{{ t.fuel_type }}</td>
                    <td>{{ t.capacity_liters }}</td>
                    <td>{{ t.temperature }}</td>
                    <td>{{ t.water_height_mm }}</td>
                    <td>{{ t.station_id }}</td>
                    <td>{{ t.product_id }}</td>

                    <td>
<span v-if="t.is_active"
      class="badge bg-success">
Active
</span>

                        <span v-else
                              class="badge bg-danger">
Inactive
</span>
                    </td>

                    <td>

                        <a v-if="canAddTank"
                           :href="`/tank/edit-tank/${t.id}`"
                           class="btn btn-primary btn-sm">
                             Edit
                        </a>

                        <button class="btn btn-danger btn-sm"
                                @click="deleteTank(t.id)">
                            Delete
                        </button>

                    </td>

                </tr>

                </tbody>

            </table>

        </div>

        <!-- ================= TANK MODAL ================= -->

        <div v-if="showTankModal"
             class="modal fade show d-block"
             style="background:rgba(0,0,0,0.6)">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>
                            {{ tankForm.id ? 'Edit Tank' : 'Add Tank' }}
                        </h5>

                        <button class="btn-close"
                                @click="closeTankModal"></button>
                    </div>

                    <div class="modal-body">

                        <input v-model="tankForm.name"
                               placeholder="Tank Name"
                               class="form-control mb-2">

                        <input v-model="tankForm.fuel_type"
                               placeholder="Fuel Type"
                               class="form-control mb-2">

                        <input v-model="tankForm.capacity"
                               type="number"
                               placeholder="Capacity"
                               class="form-control mb-2">

                        <input v-model="tankForm.current_stock"
                               type="number"
                               placeholder="Current Stock"
                               class="form-control mb-2">

                        <input v-model="tankForm.temperature"
                               type="number"
                               placeholder="Temperature"
                               class="form-control mb-2">

                        <input v-model="tankForm.fuel_mm"
                               type="number"
                               placeholder="Fuel MM"
                               class="form-control mb-2">

                        <input v-model="tankForm.water_height"
                               type="number"
                               placeholder="Water Height"
                               class="form-control">

                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-secondary"
                                @click="closeTankModal">
                            Cancel
                        </button>

                        <button class="btn btn-success"
                                @click="saveTank">
                            Save
                        </button>

                    </div>

                </div>
            </div>
        </div>

        <!-- ================================================= -->
        <!-- ================= ALARMS ======================== -->
        <!-- ================================================= -->

        <div v-if="activeTab==='alarms'">

            <div class="card shadow-lg border-0 alarm-wrapper">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">⚠ Tank Alarm Configuration</h5>

                    <!-- Email Toggle -->
                    <div class="form-check form-switch">
                        <input class="form-check-input email-switch"
                               type="checkbox"
                               v-model="alarm.is_email">
                        &nbsp;
                        <label class="form-check-label fw-semibold" style="margin-top: 4px">
                            Email Notifications
                        </label>
                    </div>
                </div>

                <div class="card-body">

                    <!-- General Settings -->
                    <h6 class="section-title">General Settings</h6>

                    <div class="row g-3 mb-4">

                        <div class="col-md-4">
                            <label class="form-label">Alarm Time (Minutes)</label>
                            <input v-model="alarm.alarm_time"
                                   type="number"
                                   class="form-control alarm-input">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">After Unloading</label>
                            <input v-model="alarm.alarm_after_unloading"
                                   type="number"
                                   class="form-control alarm-input">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Display Time (Seconds)</label>
                            <input v-model="alarm.alarm_display_time"
                                   type="number"
                                   class="form-control alarm-input">
                        </div>

                    </div>

                    <!-- Tank Level Alarm Section -->
                    <h6 class="section-title">Tank Level Alarms</h6>

                    <div class="row g-4">

                        <div class="col-md-3">
                            <div class="alarm-box low">
                                <label>Low Level</label>
                                <select v-model="alarm.tank_low_alarm" class="form-select">
                                    <option :value="1">ON</option>
                                    <option :value="0">OFF</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="alarm-box lowlow">
                                <label>Low Low Level</label>
                                <select v-model="alarm.low_low_level_alarm_mm" class="form-select">
                                    <option :value="1">ON</option>
                                    <option :value="0" selected>OFF</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="alarm-box high">
                                <label>High Level</label>
                                <select v-model="alarm.tank_low_alarm" class="form-select">
                                    <option :value="1">ON</option>
                                    <option :value="0" selected>OFF</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="alarm-box highhigh">
                                <label>High High Level</label>
                                <select v-model="alarm.low_low_level_alarm_mm" class="form-select">
                                    <option :value="1">ON</option>
                                    <option :value="0" selected>OFF</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-success save-btn"
                                @click="saveAlarm">
                            💾 Save Alarm Settings
                        </button>
                    </div>

                </div>

            </div>

        </div>
        <!-- ================================================= -->
        <!-- ================= EMAIL SETTINGS =============== -->
        <!-- ================================================= -->

        <div v-if="activeTab==='emails'">

            <div class="card p-4 shadow-sm">

                <div class="d-flex justify-content-between mb-3">
                    <h5>Email Notification List</h5>

                    <button class="btn btn-primary btn-sm"
                            @click="openEmailModal">
                        + Add Email
                    </button>
                </div>

                <table class="table table-bordered align-middle">

                    <thead class="table-dark">
                    <tr>
                        <th width="60">Active</th>
                        <th>Email</th>
                        <th width="120">Action</th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr v-for="e in emails" :key="e.id">

                        <td class="text-center">
                            <input type="checkbox"
                                   :checked="e.is_active == 1"
                                   @change="toggleEmail(e, $event)">
                        </td>

                        <td>{{ e.email }}</td>

                        <td class="text-center">
                            <button class="btn btn-danger btn-sm"
                                    @click="deleteEmail(e.id)">
                                Delete
                            </button>
                        </td>

                    </tr>

                    </tbody>

                </table>

            </div>

        </div>
        <div v-if="showEmailModal"
             class="modal fade show d-block"
             style="background:rgba(0,0,0,0.6)">

            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <div class="modal-header">
                        <h6>Add Email</h6>
                        <button class="btn-close"
                                @click="closeEmailModal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="email"
                               v-model="emailForm.email"
                               placeholder="Enter Email"
                               class="form-control">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary btn-sm"
                                @click="closeEmailModal">
                            Cancel
                        </button>
                        <button class="btn btn-success btn-sm"
                                @click="saveEmail">
                            Save
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

import axios from "axios";
import {ref} from "vue";
import Swal from 'sweetalert2'
const shift = ref({ selected: "" });
export default {

    data(){
        return {

            activeTab:'products',

            products:[],
            vendors:[],
            tanks:[],

            shift:{
                selected:''
            },

            alarm:{},
            emails: [],
            showEmailModal:false,

            emailForm:{
                id:null,
                email:'',
                is_active:1
            },
            showProductModal:false,
            showTankModal:false,

            showVendorModal:false,

            vendorForm:{
                id:null,
                VNAME:'',
                PH1:'',
                CNIC:''
            },
            productForm:{
                id:null,
                ITMNAME:'',
                UOM:'',
                PRATE:'',
                SRATE:''
            },
            showEditProductModal: false,
            addProductForm: { ITMNAME:'', UOM:'', PRATE:'', SRATE:'' },
            editProductForm: { ICODE:null, ITMNAME:'', UOM:'', PRATE:'', SRATE:'' },

            tankForm:{
                id:null,
                name:'',
                fuel_type:'',
                capacity:'',
                current_stock:'',
                temperature:'',
                fuel_mm:'',
                water_height:''
            }

        }
    },

    computed: {

        canAddTank() {
            return window.user && window.user.role == 0;
        }

    },

    mounted(){
        this.loadProducts();
        this.loadVendors();
        this.loadTanks();
        this.loadAlarm();
        this.loadShift();
        this.loadEmails();
    },

    methods:{

        /* ================= SHIFT ================= */

        loadShift(){
            axios.get('/api/settings')
                .then(res=>{
                    if(res.data && res.data.sysConfig){
                        this.shift.selected = res.data.sysConfig.NoofShifts ?? '';
                    }
                })
                .catch(err=>{
                    console.error("Shift load error", err);
                });
        },

        updateShift(type){

            if(!this.shift.selected){
                alert("Please select shift first");
                return;
            }

            let payload = {
                type: type,
                data: this.shift.selected   // ✅ IMPORTANT FIX
            };

            // console.log("Sending Payload:", payload);

            axios.post("/api/atg/shift/update", {
                NoofShifts: this.shift.selected
            })
                .then(res=>{
                    // alert("✅ NoofShifts Updated Successfully");
                    // console.log(res.data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'NoofShifts Updated Successfully',
                        confirmButtonText: 'OK'
                    });
                })
                .catch(err=>{
                    // console.log(err.response?.data);
                    // alert("❌ Update Failed");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to save shift settings'
                    });
                });

        },

        /* ================= PRODUCTS ================= */

        loadProducts(){
            axios.get('/api/products')
                .then(res=>this.products=res.data);
        },

        openProductModal(){
            this.productForm={
                id:null,
                ITMNAME:'',
                UOM:'',
                PRATE:'',
                SRATE:''
            };
            this.showProductModal=true;
        },

        editProduct(p){
            // this.productForm={...p};
            this.productForm = {
                id: p.id,
                ITMNAME: p.ITMNAME,
                UOM: p.UOM,
                PRATE: p.PRATE,
                SRATE: p.SRATE ?? ''
            };
            this.showProductModal=true;
        },

        closeProductModal(){
            this.showProductModal=false;
        },

        saveProduct(){

            if(this.productForm.id){

                axios.put('/api/atg/product/'+this.productForm.id,
                    this.productForm)
                    .then(()=>{
                        this.loadProducts();
                        this.closeProductModal();
                    });

            }else{

                axios.post('/api/atg/product',
                    this.productForm)
                    .then(()=>{
                        this.loadProducts();
                        this.closeProductModal();
                    });

            }

        },
        /* EDIT PRODUCT */
        openEditProductModal(product) {
            this.editProductForm = { ...product }; // populate form
            this.showEditProductModal = true;
        },
        closeEditProductModal() { this.showEditProductModal = false; },
        saveEditProduct() {
            if (!this.editProductForm.ICODE) {
                alert("Product ID missing. Cannot update.");
                return;
            }
            axios.put('/api/atg/product/' + this.editProductForm.ICODE, this.editProductForm)
                .then(()=>{
                    this.loadProducts();
                    this.closeEditProductModal();
                });
        },

        /* ================= VENDORS ================= */

        loadVendors(){
            axios.get('/api/atg/vendors')
                .then(res=>this.vendors=res.data);
        },
        openVendorModal(){
            this.vendorForm={
                id:null,
                VNAME:'',
                PH1:'',
                CNIC:''
            };
            this.showVendorModal=true;
        },
        closeVendorModal(){
            this.showVendorModal=false;
        },

        editVendor(v){
            this.vendorForm={...v};
            this.showVendorModal=true;
        },

        saveVendor(){

            if(!this.vendorForm.VNAME){
                alert("Vendor Name Required");
                return;
            }

            if(this.vendorForm.id){

                axios.put('/api/atg/vendor/'+this.vendorForm.id,
                    this.vendorForm)
                    .then(()=>{
                        this.loadVendors();
                        this.closeVendorModal();
                    });

            }else{

                axios.post('/api/atg/vendor',
                    this.vendorForm)
                    .then(()=>{
                        this.loadVendors();
                        this.closeVendorModal();
                    });

            }

        },
        confirmDeleteVendor(id){
            this.deleteVendorId = id;
            this.showDeleteModal = true;
        },
        deleteVendor(id){

            Swal.fire({
                title: 'Are you sure?',
                text: "This record will be deleted permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete it'
            }).then((result) => {

                if (result.isConfirmed) {

                    axios.delete('/api/atg/vendor/' + id)
                        .then(() => {

                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Record deleted successfully'
                            });

                            this.loadVendors(); // same as your existing code
                        });

                }

            });

        },
        /* ================= EMAIL SETTINGS ================= */

        loadEmails(){
            axios.get('/api/atg/emails')
                .then(res=>this.emails=res.data);
        },

        openEmailModal(){
            this.emailForm={
                id:null,
                email:'',
                is_active:1
            };
            this.showEmailModal=true;
        },

        closeEmailModal(){
            this.showEmailModal=false;
        },

        saveEmail(){

            if(!this.emailForm.email){
                Swal.fire({
                    icon:'warning',
                    title:'Email Required',
                    text:'Please enter an email address'
                });
                return;
            }

            // Email Regex Validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if(!emailPattern.test(this.emailForm.email)){
                Swal.fire({
                    icon:'error',
                    title:'Invalid Email',
                    text:'Please enter a valid email address'
                });
                return;
            }

            axios.post('/api/atg/email',this.emailForm)
                .then(()=>{

                    Swal.fire({
                        icon:'success',
                        title:'Saved',
                        text:'Email added successfully',
                        timer:1500,
                        showConfirmButton:false
                    });

                    this.loadEmails();
                    this.closeEmailModal();

                })
                .catch(()=>{

                    Swal.fire({
                        icon:'error',
                        title:'Error',
                        text:'Failed to save email'
                    });

                });
        },

        toggleEmail(e, event){

            const status = event.target.checked ? 1 : 0;

            e.is_active = status; // UI update instantly

            axios.put('/api/atg/email/' + e.id, {
                is_active: status
            })
                .then(()=>{
                    this.success("Email Status Updated");
                })
                .catch(()=>{
                    this.error("Status Update Failed");
                });

        },

        deleteEmail(id){

            Swal.fire({
                title:'Delete?',
                icon:'warning',
                showCancelButton:true
            }).then(result=>{

                if(result.isConfirmed){

                    axios.delete('/api/atg/email/'+id)
                        .then(()=>{
                            this.loadEmails();
                        });

                }

            });

        },
        /* ================= TANKS ================= */

        loadTanks(){
            axios.get('/api/tanks')
                .then(res=>this.tanks=res.data.tanks);
        },

        openTankModal(){
            this.tankForm={
                id:null,
                name:'',
                fuel_type:'',
                capacity:'',
                current_stock:'',
                temperature:'',
                fuel_mm:'',
                water_height:''
            };
            this.showTankModal=true;
        },

        closeTankModal(){
            this.showTankModal=false;
        },

        editTank(t){
            this.tankForm={...t};
            this.showTankModal=true;
        },

        saveTank(){

            if(this.tankForm.id){

                axios.put('/api/atg/tank/'+this.tankForm.id,
                    this.tankForm)
                    .then(()=>{
                        this.loadTanks();
                        this.closeTankModal();
                    });

            }else{

                axios.post('/api/atg/tank',
                    this.tankForm)
                    .then(()=>{
                        this.loadTanks();
                        this.closeTankModal();
                    });

            }

        },

        /* ================= SWEETALERT DELETE TANK ================= */

        deleteTank(id){

            Swal.fire({
                title:'Are you sure?',
                text:'Tank will be deleted permanently',
                icon:'warning',
                showCancelButton:true,
                confirmButtonText:'Yes Delete',
                confirmButtonColor:'#d33'
            }).then(result=>{

                if(result.isConfirmed){

                    axios.delete('/api/atg/tank/'+id)
                        .then(()=>{

                            this.loadTanks();

                            Swal.fire({
                                icon:'success',
                                title:'Deleted',
                                timer:1200,
                                showConfirmButton:false
                            });

                        });

                }

            });

        },

        /* ================= ALARM ================= */

        loadAlarm(){
            axios.get('/api/atg/alarm')
                .then(res=>{
                    if(res.data){
                        this.alarm=res.data;
                    }
                });
        },

        saveAlarm(){
            const payload = {
                ...this.alarm,
                is_email: this.alarm.is_email ? 1 : 0
            };
            axios.post('/api/atg/alarm',payload)
                .then(()=>{
                    // alert("Settings Saved Successfully");
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Alarm Settings Saved Successfully',
                        confirmButtonText: 'OK'
                    });
                })  .catch(() => {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save alarm settings'
                });

            });
        }

    }

}

</script>

<style scoped>

.nav-link{
    cursor:pointer;
}

.modal{
    display:block;
}
.custom-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 15%;
    min-width: 160px;
    padding: 12px 22px;
    font-size: 15px;
    font-weight: 600;
    border: none;
    border-radius: 14px;
    color: #fff;
    letter-spacing: 0.6px;
    backdrop-filter: blur(6px);              /* glass effect */
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    transition: all 0.3s ease-in-out;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

/* common hover animation */
.custom-btn::before {
    content: "";
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: rgba(255,255,255,0.25);
    transition: all 0.4s ease;
}
.custom-btn:hover::before {
    left: 100%;
}
.custom-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 12px 24px rgba(0,0,0,0.2);
}
.custom-btn:active {
    transform: scale(0.95);
}
.btn-shift {
    background: linear-gradient(135deg, #00c853, #009624);
}

.alarm-wrapper{
    border-radius:14px;
}

.section-title{
    font-weight:600;
    margin-bottom:15px;
}

.alarm-input{
    height:44px;
    border-radius:8px;
}

.alarm-box{
    padding:15px;
    border-radius:10px;
    background:#f9f9f9;
    transition:0.2s;
}

.alarm-box:hover{
    transform:scale(1.03);
}

.alarm-box label{
    font-weight:600;
    margin-bottom:6px;
    display:block;
}

.low{
    border-left:5px solid #ffc107;
}

.lowlow{
    border-left:5px solid #dc3545;
}

.high{
    border-left:5px solid #0dcaf0;
}

.highhigh{
    border-left:5px solid #ff0000;
}

.email-switch{
    width:48px;
    height:24px;
}

.save-btn{
    padding:10px 28px;
    border-radius:10px;
    font-weight:600;
}
</style>
