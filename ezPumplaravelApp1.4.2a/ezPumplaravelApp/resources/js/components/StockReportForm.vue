
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

    .table1 {
        border: 1px solid #e3e3e3;
        border-radius: 10px;
        padding: 15px 7px;
        box-shadow: 0px 0px 14px -10px black;
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

    .heading {
        font-size: 26px;
        font-weight: 800;
        margin: 1rem 0;
    }

    .main_left {
        width: 100%;
    }

    .custom_input {
        width: 100%;
        border: 2px solid black;
        border-radius: 5px;
        padding: 6px 8px;
    }

    .float_btn {
        display: flex;
        justify-content: space-between;
    }
</style>

<template>
    <h1 class="heading" style="text-align: center; font-size: 40px">Stock Reports</h1>
        <div class="main" style="display: flex; width: 100%; ">
            <div class="main_left" style="border-right: 0px;">
                <div class="table1">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th class="scope">Product Name</th>
                                <th class="scope">Previous Stock</th>
                                <th class="scope">Purchased</th>
                                <th class="scope">Total Stock</th>
                                <th class="scope">Quantity Sold</th>
                                <th class="scope">Current Stock</th>
                                <th class="scope">Sale Price</th>
                                <th class="scope">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in enginOilArray" :key="index">
                                <td><input type="text" placeholder="Product Name" v-model="item.name"></td>
                                <td><input type="text" placeholder="Prevoius Stock" v-model="item.pre_stock"></td>
                                <td><input type="text" placeholder="Purchased" v-model="item.purchased"></td>
                                <td><input type="text" placeholder="Total Stock" v-model="item.total_stock"></td>
                                <td><input type="text" placeholder="Quantity Sold" v-model="item.quantity_sold"></td>
                                <td>{{ stockDifference(index) }}</td>
                                <td><input type="text" placeholder="Sale Price" v-model="item.sale_price"></td>
                                <td><button style="background: none; border: none; cursor: pointer;" id="delete_btn"
                                        @click="EnginOilDel">❌</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="float_btn">
                        <div>
                            Total Price: <span style="font-weight: 800; color: black !important; font-size: 16px;">{{ totalSoldPrice }}rs</span>
                        </div>
                        <div>
                            <button class="main_button" @click="EnginOilMeth">Add</button>
                        </div>
                    </div>
                    <!-- @{{ enginOilArray }} -->
                     
                </div>


            </div>
        </div>
        <div class="bottom">
        </div>
    </template>
    <script>
        export default {
            name:' StockReportForm',
            data() {
                return {
                    enginOilArray: [
                        { name: '', pre_stock: '', purchased: '', total_stock: '', quantity_sold: '', current_stock: '', sale_price: '' },
                    ],
                }
            },
            computed: {
                totalprice(){
                    return this.enginOilArray.reduce((acc, item) => {
                        return acc + (parseFloat(item.sale_price) || 0);
                    }, 0);
                },
                totalSoldPrice() {
                    return this.enginOilArray.reduce((total, item) => {
                        return total + (item.quantity_sold * item.sale_price || 0);
                  }, 0);
                },
            },
            mounted() {
                const data = localStorage.getItem('enginOilArray');
                if (data) {
                    const Json = JSON.parse(data);
                    if (Array.isArray(Json)) {
                        this.enginOilArray = Json;
                    } else {
                        this.enginOilArray = [
                            { name: '', pre_stock: '', purchased: '', total_stock: '', quantity_sold: '', current_stock: '', sale_price: '' }
                        ];
                    }
                }
            },
            methods: {
                EnginOilMeth() {
                    this.enginOilArray.push({ name: '', pre_stock: '', purchased: '', total_stock: '', quantity_sold: '', current_stock: '', sale_price: '' });
                    localStorage.setItem('enginOilArray', JSON.stringify(this.enginOilArray));
                },
                EnginOilDel(index) {
                    if (!confirm("Are You Sure")) {
                        return; // just return to prevent deletion
                    }
                    this.enginOilArray.splice(index, 1);
                    localStorage.setItem('enginOilArray', JSON.stringify(this.enginOilArray));
                },
                stockDifference(index) {
                    const item = this.enginOilArray[index];
                    return (item.pre_stock || 0) - (item.quantity_sold || 0);
                },
            }
        };
    </script>