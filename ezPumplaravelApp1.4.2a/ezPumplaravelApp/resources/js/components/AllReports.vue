<template>
    <div class="main_div">
        <div class="sec_div">
            <h2 class="heading" style="text-align: left;">All Reports</h2>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in reports" :key="index">
                        <td>{{ item.id }}</td>
                        <td>{{ item.FormData }}</td>
                        {{ item.created_at }}
                    </tr>
                </tbody>
            </table>
            <div style="display: flex; justify-content: end;">
                <button class="custom_btn1">Add New</button>
            </div>
        </div>
        <h1 class="heading" style="text-align: center; font-size: 30px; font-weight: 700;">All Summary Reports</h1>
        <div class="select_table">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="sec_div">
            <h2 class="heading" style="text-align: left;">Tank Stock</h2>
            <table class="table">
                <thead class="thead-light">
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
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in TankStock" :key="index" class="table_tr">
                        <td>{{ item.tankName }}</td>
                        <td>{{ item.product }}</td>
                        <td>{{ item.prevDip }}</td>
                        <td>{{ item.prevDipLitre }}</td>
                        <td>{{ item.buy }}</td>
                        <td>{{ item.sale }}</td>
                        <td>{{ item.currentDip }}</td>
                        <td>{{ item.currentDipLitre }}</td>
                        <td>{{ item.gainLoss }}</td>
                    </tr>
                </tbody>
            </table>
            <div style="display: flex; justify-content: end;">
                <button class="custom_btn1">Add New</button>
            </div>
        </div>
        <div class="sec_div">
            <h2 class="heading" style="text-align: left;">Net Sale / Receipts</h2>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in Sales" :key="index" class="table_tr">
                        <td>{{ item.item }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.amount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="sec_div">
            <h2 class="heading" style="text-align: left;">Credit / Payments</h2>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Item Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in Credit" :key="index" class="table_tr">
                        <td>{{ item.item }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.amount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="sec_div">
            <h2 class="heading" style="text-align: left;">MCB / UBL / PSO / Credit Cards</h2>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Credit Cards</th>
                        <th scope="col">Other</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in CreditCard" :key="index" class="table_tr">
                        <td>{{ item.item }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.amount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="sec_div">
            <h2 class="heading" style="text-align: left;">Summary Cash Payments</h2>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Item</th>
                        <th scope="col">Other</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in CashExpense" :key="index" class="table_tr">
                        <td>{{ item.item }}</td>
                        <td>{{ item.other }}</td>
                        <td>{{ item.amount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="sec_div">
            <h2 class="heading" style="text-align: left;">All Expenses</h2>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" style="width: 70%;">Expense</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in Expense" :key="index" class="table_tr">
                        <td>{{ item.item }}</td>
                        <td>{{ item.amount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="sec_div">
            <h2 class="heading" style="text-align: left;">Credit Customers</h2>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Product</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in CredCustom" :key="index" class="table_tr">
                        <td>{{ item.name }}</td>
                        <td>{{ item.product }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.amount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="sec_div">
            <h2 class="heading" style="text-align: left;">Received Payment (Wasooli)</h2>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in PayReciAmount" :key="index" class="table_tr">
                        <td>{{ item.name }}</td>
                        <td>{{ item.amount }}</td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
    <!-- <div class="button_botm">
        <button class="custom_btn1"><i class="lni lni-close m-1"></i> Cancel</button>
        <div style="display: flex; gap: 0px 10px;">
            <button class="custom_btn1" style="background: #0b00ff;"><i class="lni lni-pencil m-1"></i> Edit</button>
            <button class="custom_btn1" style="background: #009e00;"><i class="lni lni-checkmark m-1"></i> Update</button>
        </div>
    </div> -->

</template>


<style scoped>
.button_botm {
    display: flex;
    justify-content: space-between;
    position: fixed;
    bottom: 15px;
}

.custom_btn1 {
    border: none;
    background: #404040;
    color: white;
    border-radius: 7px;
    padding: 5px 15px;
    cursor: pointer;
    transition: 300ms;
}

.custom_btn1:hover {
    transform: scale(1.04);
}

.table_tr td {
    text-align: left !important;
}

.sec_div {
    margin-top: 1rem;
    background: white;
    border-radius: 7px;
    border: 1px solid #cdcdcd;
    padding: 15px;
    box-shadow: 0px 0px 11px -9px black;
}
</style>

<script>
export default {
    data() {
        return {
            page: 1,
          reports: [],
        };
    },
    mounted() {
        this.FetchApi();
    },
    methods: {
        FetchApi() {
            fetch('/api/summary-reports')
                .then(res => res.json())
                .then(data => {
                   this.reports = data;
                });
        },
    }
};
</script>