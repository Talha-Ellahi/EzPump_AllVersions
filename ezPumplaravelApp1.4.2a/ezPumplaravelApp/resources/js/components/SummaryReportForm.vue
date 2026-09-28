<style lang="css" scoped>
.main span {
    font-weight: 800;
    color: black !important;
}
</style>
<style>
.main_button {
    border: none;
    background: #212529;
    color: white;
    padding: 6px 31px;
    cursor: pointer;
    border-radius: 5px;
    transition: 300ms;
}

.prev_btn:hover {
    background: red !important;
}

.table1 {
    border: 1px solid #e3e3e3;
    border-radius: 10px;
    padding: 15px 7px;
    box-shadow: 0px 0px 14px -10px black;
    background: white !important;
}

.main_button:hover {
    background: gray;
}

.main_button:active {
    transform: scale(1.1);
}

.table td,
.table th {
    padding: 6px;
}

.main table input {
    font-size: 12px !important;
}

.heading {
    font-size: 21px;
    font-weight: 800;
    margin: 0px !important;
    margin-bottom: 10px !important;
}

.main_left {
    width: 50%;
    border-right: 1px solid gray;
    padding-right: 10px;
}

.custom_input {
    width: 100%;
    border: 2px solid black;
    border-radius: 5px;
    padding: 6px 8px;
}

.custom_input2 {
    width: 10rem;
    border: 2px solid black;
    border-radius: 5px;
    padding: 6px 8px;
}

.custom_input3 {
    width: 10rem;
    border: 1px solid black;
    border-radius: 5px;
    padding: 6px 8px;
}

.float_btn {
    display: flex;
    justify-content: space-between;
}

.main input {
    padding: 5px !important;
    border-radius: 2px !important;
    border: 1px solid gray !important;
}

.table1 {
    margin: 20px 0px;
}



.left {
    background: #ff00007d;
    position: fixed;
    width: 8px;
    height: 80px;
    top: 50%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0px 0px 14px -5px black;
    border-radius: 0px 10px 10px 0px;
    align-items: center;
    transition: 400ms;
    left: 0;
}

.right {
    background: #00800078;
    position: fixed;
    width: 8px;
    height: 80px;
    top: 50%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    right: 0;
    cursor: pointer;
    box-shadow: 0px 0px 14px -5px black;
    transition: 400ms;
    border-radius: 10px 0px 0px 10px;
    align-items: center;
}

#right_text {
    display: none !important;
    text-align: center;
    align-items: center;
}

#text_left {
    display: none !important;
    text-align: center;
    align-items: center;
}

.right:hover #right_text {
    display: flex !important;
}

.right:hover {
    width: 50px;
}

.left:hover #text_left {
    display: flex !important;
}

.left:hover {
    width: 50px;
}

#right_sec td input {
    width: 9rem;
}

.next_btn {
    border: none;
    background: #0080007d;
    color: white;
    padding: 8px 23px;
    border-radius: 6px;
    cursor: pointer;
    transition: 300ms;
}

.next_btn:hover {
    background: rgb(0, 162, 0);
}
</style>
<template>
    <div id="let_sect" v-if="page === 2">

        <h1 class="heading" style="text-align: center; font-size: 40px">Ez-Pump Summary</h1>
        <div style="display:flex; gap:20px; align-items:flex-end; margin-bottom:15px;">

            <!-- Shift Date -->
            <div>
                <label style="font-weight:600;">Shift Date</label><br>
                <select v-model="selectedDate"
                        @change="onDateChange"
                        style="padding:6px 10px; min-width:220px;">
                    <option disabled value="">Select Date</option>
                    <option v-for="date in uniqueDates" :key="date" :value="date">
                        {{ date }}
                    </option>
                </select>
            </div>

            <!-- Calendar ID -->
            <div v-if="calendarIds.length > 0">
                <label style="font-weight:600;">Calendar ID</label><br>
                <select v-model="calendar_id"
                        style="padding:6px 10px; min-width:220px;">
                    <option disabled value="">Select Calendar ID</option>
                    <option v-for="cal in calendarIds" :key="cal.id" :value="cal.id">
                        {{ cal.id }}
                    </option>
                </select>
            </div>

        </div>

        <div class="main" style="display: flex; width: 100%; ">

            <div class="main_left">
                <div class="table1">
                    <h1 class="heading">Summary Net Sale / Receipts</h1>
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in AddSalesArray" :key="index">
                            <td><input type="text" placeholder="Item Name" v-model="item.item"></td>
                            <td><input type="number" min="0" placeholder="Item Quantity" v-model="item.quantity">
                            </td>
                            <td><input type="number" min="0" placeholder="Item Amount" v-model="item.amount"></td>
                            <td><button @click="deleteSalesProduct(index)"
                                        style="background: none; border: none; cursor: pointer;"
                                        id="delete_btn">❌</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="float_btn">
                        <div>
                            <span style="font-weight: 800;">Total Price :</span> {{AddSalesArray.reduce((a, b) => a +
                            (+b.amount || 0), 0)}}
                        </div>
                        <div>
                            <button class="main_button" @click="addSalesProduct">Add</button>
                        </div>
                    </div>
                </div>
                <div class="table1 table2">
                    <h1 class="heading">Summary Credit / Payments</h1>
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Item Name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in CreditSalesArray" :key="index">
                            <td><input type="text" v-model="item.item" placeholder="Item Name"></td>
                            <td><input type="number" min="0" v-model="item.quantity" placeholder="Item Quantity">
                            </td>
                            <td><input type="number" v-model="item.amount" placeholder="Item Amount" min="0"></td>
                            <td><button @click="deleteCreditSales(index)"
                                        style="background: none; border: none; cursor: pointer;"
                                        id="delete_btn">❌</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="float_btn">
                        <div>
                            <span style="font-weight: 800;">Total Price :</span> {{CreditSalesArray.reduce((a, b) => a
                            + (+b.amount || 0), 0)}}
                        </div>
                        <button class="main_button" @click="CreditSales">Add</button>
                    </div>
                </div>
                <div class="table1 table3">
                    <h1 class="heading">MCB / UBL / PSO / Credit Cards</h1>
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Credit Cards</th>
                            <th scope="col">Other</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in CreditArray" :key="index">
                            <td><input type="text" placeholder="Card Name" v-model="item.item"></td>
                            <td><input type="text" placeholder="Other" v-model="item.quantity"></td>
                            <td><input type="text" placeholder="Amount" v-model="item.amount"></td>
                            <td><button @click="CreditDel(index)"
                                        style="background: none; border: none; cursor: pointer;"
                                        id="delete_btn">❌</button>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                    <div class="float_btn">
                        <div>
                            <span style="font-weight: 800;">Total Price :</span> {{CreditArray.reduce((a, b) => a +
                            (+b.amount || 0), 0)}}
                        </div>
                        <button class="main_button" @click="CreditMeth">Add</button>
                    </div>
                </div>
                <div class="table1 table4">
                    <h1 class="heading">Summary Cash Payments</h1>
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Item</th>
                            <th scope="col">Other</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in CashExpenseArry" :key="index">
                            <td><input type="text" placeholder="Name" v-model="item.item"></td>
                            <td><input type="text" placeholder="Other" v-model="item.other"></td>
                            <td><input type="text" placeholder="Amount" v-model="item.amount"></td>
                            <td><button @click="CashExpenseDel(index)"
                                        style="background: none; border: none; cursor: pointer;"
                                        id="delete_btn">❌</button>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                    <div class="float_btn">
                        <div>
                            <span style="font-weight: 800;">Total Price :</span> {{CashExpenseArry.reduce((a, b) => a
                            + (+b.amount || 0), 0)}}
                        </div>
                        <button class="main_button" @click="CashExpense">Add</button>
                    </div>
                </div>

            </div>


            <div class="main_right" style="width: 50%; padding-left: 5px; padding-right: 5px;">
                <div>
                    <div class="table1">
                        <h1 class="heading">All Expenses</h1>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col" style="width: 70%;">Expense</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in ExpenseArray" :key="index">
                                <td><input type="text" class="custom_input" placeholder="Expense Name"
                                           v-model="item.item">
                                </td>
                                <td><input type="text" class="custom_input" placeholder="Expense Amount"
                                           v-model="item.amount"></td>
                                <td><button @click="ExpenseDel(index)"
                                            style="background: none; border: none; cursor: pointer;"
                                            id="delete_btn">❌</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="float_btn">
                            <div>
                                <span style="font-weight: 800;">Total Price :</span> {{ExpenseArray.reduce((a, b) => a +
                                (+b.amount || 0), 0)}}
                            </div>
                            <button class="main_button" @click="ExpenseMethod">Add</button>
                        </div>
                    </div>
                    <div class="table1">
                        <h1 class="heading">Credit Customers</h1>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in CreditCustomersArray" :key="index">
                                <td style="width: 150px;">
                                    <Multiselect
                                        v-model="item.customer_id"
                                        :options="customerOptions"
                                        :filter-results="false"
                                        :min-chars="1"
                                        :resolve-on-load="false"
                                        :delay="500"
                                        searchable
                                        placeholder="Search customer"
                                        @search-change="fetchCustomers"
                                        style="width: 150px;"

                                    >

                                    </Multiselect>
                                </td>
                                <td><select v-model="item.product" class="custom_input3">
                                    <option disabled value="">Select Product</option>
                                    <option v-for="product in productOptions" :key="product" :value="product">
                                        {{ product }}
                                    </option>
                                </select></td>
                                <td><input v-model="item.quantity" class="custom_input2" type="text"
                                           placeholder="Quantity"></td>
                                <td><input v-model="item.amount" class="custom_input2" type="text"
                                           placeholder="Amount">
                                </td>
                                <td><button @click="creditCustomerDel(index)"
                                            style="background: none; border: none; cursor: pointer;"
                                            id="delete_btn">❌</button></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="float_btn">
                            <div>
                            </div>
                            <button class="main_button" @click="CreditCustomers()">Add</button>
                        </div>
                    </div>
                    <div class="table1">
                        <h1 class="heading">Received Payment (Wasooli)</h1>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in PaymentReciArray" :key="index">
                                <td><input v-model="item.name" type="text" placeholder="Name"></td>
                                <td><input type="text" v-model="item.amount" placeholder="Amount"></td>
                                <td><button @click="PaymentReciDel(index)"
                                            style="background: none; border: none; cursor: pointer;"
                                            id="delete_btn">❌</button></td>

                            </tr>
                            </tbody>
                        </table>
                        <div class="float_btn">
                            <div>
                            </div>
                            <button class="main_button" @click="PaymentReci()">Add</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <div id="right_sec" v-if="page === 1">
        <div style="display: flex; align-items: center; gap: 0px 10px;">
        </div>
        <div style="
    border: 1px solid #e3e3e3;
    border-radius: 10px;
    background: white !important;
    padding: 15px 7px;
    box-shadow: 0px 0px 14px -10px black;
    ">
            <h1 class="heading" style="margin-bottom: 3px !important; font-size: 20px;"> Nozel Details</h1>

            <ShiftChangeReport v-model="Form1" />
        </div>
        <div class="table1" style="margin-top: 7px; padding: 10px 7px  !important;">
            <h1 class="heading" style="margin-bottom: 0px !important; font-size: 20px;">Tank Stock</h1>
            <table class="table table1" style="margin: 7px 0px !important">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Tank Name</th>
                    <th scope="col">Product</th>
                    <th scope="col">Prev Dip</th>
                    <th scope="col">Prev Dip Litre</th>
                    <th scope="col">Buy</th>
                    <th scope="col">Sale</th>
                    <th scope="col">Current Dip</th>
                    <th scope="col">Current Dip Litre</th>
                    <th scope="col">Gain / Loss</th>
                    <th scope="col">Delete</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in tankStockArray" :key="index">
                    <td><input v-model="item.tankName" type="text" placeholder="Tank Name" style="width: 100%;">
                    </td>
                    <td><input v-model="item.product" type="text" placeholder="Product"></td>
                    <td><input v-model="item.prevDip" type="text" placeholder="Prev Dip"></td>
                    <td><input v-model="item.prevDipLitre" type="text" placeholder="Prev Dip Litre"></td>
                    <td><input v-model="item.buy" type="text" placeholder="Buy"></td>
                    <td><input v-model="item.sale" type="text" placeholder="Sale"></td>
                    <td><input v-model="item.currentDip" type="text" placeholder="Current Dip"></td>
                    <td><input v-model="item.currentDipLitre" type="text" placeholder="Current Dip Litre"></td>
                    <td><input disabled v-model="item.gainLoss" type="text" placeholder="Gain / Loss"></td>
                    <td><button style="background: none; border: none; cursor: pointer;" id="delete_btn"
                                @click="TankStockDel(index)">❌</button></td>

                </tr>
                </tbody>
            </table>
            <div class="float_btn">
                <div>
                    <span style="font-weight: 800;">Total Price :</span>
                </div>
                <button class="main_button" @click="TankStockMeth">Add</button>
            </div>
            <!-- {{ tankStockArray }} -->
        </div>
    </div>
    <div class="buttons"
         style="display: flex; justify-content: space-between; align-items: end; position: fixed; z-index: 9999; bottom: 10px;">
        <div v-if="page === 1" style="position: absolute; right: 20px; bottom: 0px;"><button @click="nextPage()"
                                                                                             class="next_btn" style="justify-content: end;">Next</button></div>

        <div v-if="page === 2" style="position: absolute; right: 20px; bottom: 0px;"><button @click="SubmitForm()"
                                                                                             class="next_btn" style="justify-content: end;">Submit</button></div>
        <div v-if="page === 2"><button @click="prevPage()" class="next_btn prev_btn"
                                       style="background: #ff000099;">Back</button></div>
    </div>
</template>
<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import Multiselect from '@vueform/multiselect'
import ShiftChangeReport from './ShiftChangeReport.vue'
import AllReports from './AllReports.vue'
import '@vueform/multiselect/themes/default.css'
const page = ref(1)
const AddSalesArray = ref([{ item: "", quantity: "", amount: "" }])
const CreditSalesArray = ref([{ item: "", quantity: "", amount: "" }])
const ExpenseArray = ref([{ item: "", amount: "" }])
const CreditArray = ref([{ item: '', quantity: '', amount: '' }])
const CashExpenseArry = ref([{ item: '', other: '', amount: '' }])
const fuelStockArray = ref([{ product: '', previousDip: '', liters: '', purchase: '', sale: '', currentDip: '', litre2: '', profitLoss: '' }])
const PaymentReciArray = ref([{ name: '', amount: '' }])
const CreditCustomersArray = ref([{ customer_id: '', name: '', product: '', quantity: '', amount: '' }])
const customerOptions = ref([])
const customerSearchCache = ref({})
const productOptions = ref([])
const calendarDates = ref([]) // API full data
const selectedDate = ref('') // date dropdown v-model
const calendarIds = ref([]) // filtered calendar IDs
const calendar_id = ref(null) // selected calendar ID

// Unique dates for date dropdown
const uniqueDates = computed(() => {
    const dates = calendarDates.value.map(c => c.work_date)
    return [...new Set(dates)].sort((a,b) => new Date(b) - new Date(a)) // latest first
})

// Fetch all shift calendars on mount
onMounted(async () => {
    try {
        const res = await fetch('/api/shift-calendars')
        if(res.ok){
            const data = await res.json()
            calendarDates.value = data

            // 🔹 Auto-select latest date in date dropdown
            if(uniqueDates.value.length){
                selectedDate.value = uniqueDates.value[0]
                onDateChange() // populate calendar IDs for this date
            }
        }
    } catch(e){
        console.error('Failed to fetch shift calendars', e)
    }
})

// Called when date changes
function onDateChange(){
    calendarIds.value = calendarDates.value.filter(c => c.work_date === selectedDate.value)
    if(calendarIds.value.length){
        calendar_id.value = calendarIds.value[0].id // auto select first ID
    } else {
        calendar_id.value = null
    }
}
// onMounted(async () => {
//     fetchShiftCalendars()
//
//     // 👇 aapka existing onMounted code as-it-is rahe
// })


async function fetchCustomers(query) {
    if (!query) return

    if (customerSearchCache.value[query]) {
        customerOptions.value = customerSearchCache.value[query]
        return
    }

    try {
        const response = await fetch(`/api/customers?search=${query}`)
        if (response.ok) {
            const data = await response.json()
            // Map to { value, label }
            const mapped = data.map(c => ({
                value: c.customer_id,
                label: c.Des
            }))
            customerOptions.value = mapped
            customerSearchCache.value[query] = mapped
        }
    } catch (error) {
        console.error('Failed to fetch customers:', error)
    }
}
const tankStockArray = ref([{ tankName: '', product: '', prevDip: '', prevDipLitre: '', buy: '', sale: '', currentDip: '', currentDipLitre: '', gainLoss: '' }])
const FetchArray = ref([])
const Form1 = ref([{ opening: '', closing: '', total: '', image: '' }])

const formData = computed(() => ({
    calendar_id: calendar_id.value,
    sales: AddSalesArray.value,
    credit: CreditSalesArray.value,
    nozel_details: Form1.value,
    expense: ExpenseArray.value,
    cash_expense: CashExpenseArry.value,
    fuel_stock: fuelStockArray.value,
    pay_rei_am: PaymentReciArray.value,
    credit_customers: CreditCustomersArray.value,
    tank_stock: tankStockArray.value,
    credit_cards: CreditArray.value
}))

const totalB = computed(() => {
    return [
        CreditSalesArray.value,
        CreditArray.value,
        CashExpenseArry.value
    ].reduce((sum, arr) => {
        return sum + arr.reduce((s, i) => s + Number(i.amount || 0), 0)
    }, 0)
})

const afterMinus = computed(() => {
    let subtractTotal = AddSalesArray.value.reduce((s, i) => s + Number(i.amount || 0), 0)
    return totalB.value - subtractTotal
})

// Calculate gain/loss for tank stock
const calculateGainLoss = (tank) => {
    const prev = parseFloat(tank.prevDipLitre) || 0
    const buy = parseFloat(tank.buy) || 0
    const sale = parseFloat(tank.sale) || 0
    const current = parseFloat(tank.currentDipLitre) || 0
    const final=prev + buy - sale;
    return (current-final).toFixed(2)
    // return (prev + buy - sale - current).toFixed(2)
}

// Watch for changes in tank stock fields and update gain/loss
watch(tankStockArray, (newVal) => {
    newVal.forEach(tank => {
        tank.gainLoss = calculateGainLoss(tank)
    })
}, { deep: true })

onMounted(async () => {
    // Fetch pumps data from API
    try {
        const response = await fetch('/api/pumps')
        if (response.ok) {
            const pumps = await response.json()
            Form1.value = pumps.map(pump => ({
                opening: '',
                closing: '',
                total: '',
                image: '',
                ...pump  // Include any pump properties if needed
            }))
        }
    } catch (error) {
        console.error('Failed to fetch pumps:', error)
    }

    // Fetch tanks data from API
    try {
        const response = await fetch('/api/tanks')
        if (response.ok) {
            const tanks = await response.json()
            tankStockArray.value = tanks.map(tank => ({
                tankName: tank.tank_name || '',
                product: tank.product || '',
                prevDip: '',
                prevDipLitre: '',
                buy: '',
                sale: '',
                currentDip: '',
                currentDipLitre: '',
                gainLoss: '',
                ...tank  // Include any additional tank properties
            }))
        }
    } catch (error) {
        console.error('Failed to fetch tanks:', error)
    }
    // Fetch products data from API
    try {
        const response = await fetch('/api/products')
        if (response.ok) {
            const products = await response.json()
            AddSalesArray.value = products.map(product => ({
                item: product.ITMNAME || '',
                quantity: '',
                amount: '',
                ...product  // Include any product properties if needed
            }))
            // Store product options for dropdown
            productOptions.value = products.map(p => p.ITMNAME)
        }
    } catch (error) {
        console.error('Failed to fetch products:', error)
    }

    // Fetch payment methods data from API
    try {
        const response = await fetch('/api/payment-methods')
        if (response.ok) {
            const paymentMethods = await response.json()
            CreditArray.value = paymentMethods
                .filter(method => method.erp_id !== 1)
                .map(method => ({
                    item: method.Des || '',
                    quantity: '',
                    amount: '',
                    ...method  // Include any payment method properties if needed
                }))
        }
    } catch (error) {
        console.error('Failed to fetch payment methods:', error)
    }

    const data2 = localStorage.getItem("formData2")
    if (data2) {
        const parsedData2 = JSON.parse(data2)
        if (Array.isArray(parsedData2)) {
            CreditSalesArray.value = parsedData2
        } else {
            CreditSalesArray.value = [{ item: "", quantity: "", amount: "" }]
        }
    }

    const data3 = localStorage.getItem("formExpense")
    if (data3) {
        const parsedData3 = JSON.parse(data3)
        if (Array.isArray(parsedData3)) {
            ExpenseArray.value = parsedData3
        } else {
            ExpenseArray.value = [{ item: '', amount: '' }]
        }
    }

    const data4 = localStorage.getItem("Credit")
    if (data4) {
        const jsonParsed4 = JSON.parse(data4)
        if (Array.isArray(jsonParsed4)) {
            CreditArray.value = jsonParsed4
        } else {
            CreditArray.value = [{ item: '', quantity: '', amount: '' }]
        }
    }

    const data5 = localStorage.getItem("CashExpense")
    if (data5) {
        const jsonParsed5 = JSON.parse(data5)
        if (Array.isArray(jsonParsed5)) {
            CashExpenseArry.value = jsonParsed5
        } else {
            CashExpenseArry.value = [{ item: '', other: '', amount: '' }]
        }
    }

    const data6 = localStorage.getItem("fuelStock")
    if (data6) {
        const jsonParsed6 = JSON.parse(data6)
        if (Array.isArray(jsonParsed6)) {
            fuelStockArray.value = jsonParsed6
        } else {
            fuelStockArray.value = [{ product: '', previousDip: '', liters: '', purchase: '', sale: '', currentDip: '', litre2: '', profitLoss: '' }]
        }
    }

    const data7 = localStorage.getItem("formdata778")
    if (data7) {
        const jsonParsed7 = JSON.parse(data7)
        if (Array.isArray(jsonParsed7)) {
            PaymentReciArray.value = jsonParsed7
        } else {
            PaymentReciArray.value = [{ name: '', amount: '' }]
        }
    }

    // Initialize select2 for customer dropdowns


    const data8 = localStorage.getItem("CreditCustomers")
    if (data8) {
        const jsonParsed8 = JSON.parse(data8)
        if (Array.isArray(jsonParsed8)) {
            CreditCustomersArray.value = jsonParsed8

        } else {
            CreditCustomersArray.value = [{ customer_id: '', name: '', product: '', quantity: '', amount: '' }]
        }
    }


})

function addSalesProduct() {
    AddSalesArray.value.push({ item: '', quantity: '', amount: '' })
    localStorage.setItem("formData", JSON.stringify(AddSalesArray.value))
}

function deleteSalesProduct(index) {
    if (!confirm("Are Your Sure")) return
    AddSalesArray.value.splice(index, 1)
    localStorage.setItem("formData", JSON.stringify(AddSalesArray.value))
}

function CreditSales() {
    CreditSalesArray.value.push({ item: '', quantity: '', amount: '' })
    localStorage.setItem("formData2", JSON.stringify(CreditSalesArray.value))
}

function deleteCreditSales(index) {
    if (!confirm("Are Your Sure")) return
    CreditSalesArray.value.splice(index, 1)
    localStorage.setItem("formData2", JSON.stringify(CreditSalesArray.value))
}

function ExpenseMethod() {
    ExpenseArray.value.push({ item: '', amount: '' })
    localStorage.setItem("formExpense", JSON.stringify(ExpenseArray.value))
}

function ExpenseDel(index) {
    if (!confirm("Are Your Sure")) return
    ExpenseArray.value.splice(index, 1)
    localStorage.setItem("formExpense", JSON.stringify(ExpenseArray.value))
}

function CreditMeth() {
    CreditArray.value.push({ item: '', quantity: '', amount: '' })
    localStorage.setItem("Credit", JSON.stringify(CreditArray.value))
}

function CreditDel(index) {
    if (!confirm("Are Your Sure")) return
    CreditArray.value.splice(index, 1)
    localStorage.setItem("Credit", JSON.stringify(CreditArray.value))
}

function CashExpense() {
    CashExpenseArry.value.push({ item: '', other: '', amount: '' })
    localStorage.setItem("CashExpense", JSON.stringify(CashExpenseArry.value))
}

function CashExpenseDel(index) {
    if (!confirm("Are Your Sure")) return
    CashExpenseArry.value.splice(index, 1)
    localStorage.setItem("CashExpense", JSON.stringify(CashExpenseArry.value))
}

function PaymentReci() {
    PaymentReciArray.value.push({ name: '', amount: '' })
    localStorage.setItem("formdata778", JSON.stringify(PaymentReciArray.value))
}

function PaymentReciDel(index) {
    if (!confirm("Are Your Sure")) return
    PaymentReciArray.value.splice(index, 1)
    localStorage.setItem("formdata778", JSON.stringify(PaymentReciArray.value))
}

function addFuelStock() {
    fuelStockArray.value.push({
        product: '', previousDip: '', liters: '',
        purchase: '', sale: '', currentDip: '',
        litre2: '', profitLoss: ''
    })
    localStorage.setItem("fuelStock", JSON.stringify(fuelStockArray.value))
}

function FuelStockDel(index) {
    if (!confirm("Are You Sure")) return
    fuelStockArray.value.splice(index, 1)
    localStorage.setItem("fuelStock", JSON.stringify(fuelStockArray.value))
}

function CreditCustomers() {
    CreditCustomersArray.value.push({ customer_id: '', name: '', product: '', quantity: '', amount: '' })
    localStorage.setItem("CreditCustomers", JSON.stringify(CreditCustomersArray.value))
}

function creditCustomerDel(index) {
    if (!confirm("Are You Sure")) return
    CreditCustomersArray.value.splice(index, 1)
    localStorage.setItem("CreditCustomers", JSON.stringify(CreditCustomersArray.value))
}

function nextPage() {
    if (page.value < 2) page.value++
}

function prevPage() {
    if (page.value > 1) page.value--
}

function TankStockMeth() {
    tankStockArray.value.push({ tankName: '', product: '', prevDip: '', prevDipLitre: '', buy: '', sale: '', currentDip: '', currentDipLitre: '', gainLoss: '' })
    localStorage.setItem("tankStock", JSON.stringify(tankStockArray.value))
}

function TankStockDel(index) {
    if (!confirm("Are You Sure")) return
    tankStockArray.value.splice(index, 1)
    localStorage.setItem("tankStock", JSON.stringify(tankStockArray.value))
}

function SubmitForm() {
    // if (!calendar_id.value) {
    //     alert('Please select Calendar ID')
    //     return
    // }
    fetch('/api/summary-report-form', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData.value),
    })
        .then(response => {
            if (response.ok) {
                alert("Form submitted successfully!")
                page.value = 1
            } else {
                alert("Failed to submit form.")
            }
        })
}
</script>
