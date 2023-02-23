<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css">
    <title>LR1</title>
</head>
<body>
    <div id="app">
        <div class="control-panel">
            <h3>Шаг 1. Определите количество вершин</h3>
            <div class="form-field">
                <label for="countVertex">Количество вершин</label>
                <input type="number" name="countVertex" id="" v-model="countVertex" min=0 @change="addDataInput">
            </div>
        </div>

        <div class="data-panel">
            <h3>Шаг 2. Введите множество левых инцеденций G<sup>-1</sup>(i)</h3>
            <p class="info">Матрица G<sup>-1</sup>(i) левых инциденций определяет все вершины, из которых можно попасть в вершину i</p>
            <p class="teh-info">Введите вершины через ", " (запятая и пробел).</p>

            <div class="input-items">
                <div class="form-field" v-for="(item, index) in arrData">
                    <label :for="'data-' + index">G({{ index + 1 }})</label>
                    <input type="text" :name="'data-' + index" id="" v-model="arrData[index]" placeholder="1, 3">
                </div>
            </div>

            <button @click='getResult()' @keyup.enter="getResult()">Результат</button>
        </div>

        <div class="result-panel" v-if='flag'>
            <h3>Шаг 3. Результаты вычислений</h3>

            <div class="tabs">
            <span class="tab" 
                    v-for="(tab, index) in tabs" 
                    @click="selectedTab = index"
                    :class='selectedTab == index ? "active-tab" : ""'
            >{{ tab }}</span>
            </div>

            <div class="result-adjacency-matrix tab-content" :class='selectedTab == 0 ? "active-tab" : ""'>
                <h4>3.1 Матрица смежности А</h4>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th v-for="(item, index) in arrData">{{index + 1}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(row, index) in adjacencyMatrix">
                            <th>{{index + 1}}</th>
                            <td v-for="(item, ind) in row">{{item}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="result-incidence-matrix tab-content" :class='selectedTab == 1 ? "active-tab" : ""'>
                <h4>3.2 Матрица инциденций В</h4>
                <div class="table">
                    <div class="table-col">
                        <div> </div>
                        <div class='th' v-for="(item, index) in arrData">{{index + 1}}</div>
                    </div>

                    <div class="table-col" v-for="(row, name) in incidenceMatrix">
                        <div class='th'>{{name}}</div>
                        <div v-for="(item, index) in row">{{item}}</div>
                    </div>
                </div>
            </div>
        </div>
        <p class="result-panel__no-data teh-info" v-else>
            Для получения результата введите корректные данные и нажмите кнопку "Результат".
        </p>
    </div>
    
    <script src="/plagins/vue3.0/vue.js"></script>
    <script src="/plagins/axios/axios.js"></script>
    <script src="/script.js"></script>
</body>
</html>