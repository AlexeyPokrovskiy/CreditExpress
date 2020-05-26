<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Курс валют на сегодня</div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Базовая валюта</th>
                                <th scope="col">Целевая валюта</th>
                                <th scope="col">Покупка</th>
                                <th scope="col">Продажа</th>
                            </tr>
                            </thead>
                            <tbody v-if="currentExchange.length" >
                            <tr v-for="(item, index) in currentExchange">
                                <th scope="row">{{index+1}}</th>
                                <td>{{item.base_ccy}}</td>
                                <td>{{item.ccy}}</td>
                                <td>{{item.buy}}</td>
                                <td>{{item.sale}}</td>

                            </tr>
                            </tbody>
                            <tbody v-else>
                            <tr>
                                <td colspan="5" class="text-center">Не было найдено никаких результатов</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header">Архив валют</div>

                    <div class="card-body">
                        <p v-if="errors.length">
                            <b>Проверьте правильность введеных данных:</b>
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                        </p>
                        <div class="form-arhive" >
                            <label for="date">Выберите дату</label>
                            <input  class="form-control" v-model="arhiveData" type="date" id="date" value="2020-05-26" >
                            <button type="submit" @click.prevent="fetchArchive" class="btn btn-success d-flex"><i v-if="busy" class="fa fa-spin fa-spinner"></i><span class="filter-fetch-button">Получить</span></button>
                        </div>
                        <br>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Базовая валюта</th>
                                <th scope="col">Целевая валюта</th>
                                <th scope="col">Покупка</th>
                                <th scope="col">Продажа</th>
                            </tr>
                            </thead>
                            <tbody v-if="arhiveExchange.length" >
                            <tr v-for="item in arhiveExchange" v-if="item.currency && item.purchaseRate  && item.saleRate">

                                <td>{{item.baseCurrency}}</td>
                                <td>{{item.currency}}</td>
                                <td>{{item.purchaseRate}}</td>
                                <td>{{item.saleRate}}</td>

                            </tr>
                            </tbody>
                            <tbody v-else>
                            <tr>
                                <td colspan="5" class="text-center">Не было найдено никаких результатов</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                currentExchange: [],
                arhiveExchange: [],
                arhiveData: null,
                busy: false,
                errors:[],
            }
        },
        methods: {
            fetchArchive() {
                this.errors = []
                if(!this.arhiveData){
                    this.errors.push("Дата обязательна к вводу.")
                    return false;
                }

                this.busy = true;
                axios.get('/api/archive-exchenge',{
                    params: {
                        date: this.arhiveData,
                    }
                }).then(response => {
                    this.arhiveExchange = response.data.exchangeRate;
                    this.busy = false;
                })
            },


            fetchCurrent() {
                axios.get('/api/current-exchenge')
                    .then(response => {
                      this.currentExchange = response.data;
                    })
            }
        },
        mounted() {
            this.fetchCurrent();
        }
    }
</script>
