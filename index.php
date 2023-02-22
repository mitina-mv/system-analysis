<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <h3>Шаг 2. Матрица левых инцеденций G<sup>-1</sup>(i)</h3>
            <p class="info">Матрица G<sup>-1</sup>(i) левых инциденций определяет все вершины, из которых можно попасть в вершину i</p>
            <p class="teh-info">Введите вершины через ", " (запятая и пробел)</p>

            <div class="input-items">
                <div class="form-field" v-for="(item, index) in arrData">
                    <label :for="'data-' + index">G({{ index + 1 }})</label>
                    <input type="text" :name="'data-' + index" id="" v-model="arrData[index]">
                </div>
            </div>

            <button @click='getResult()' @keyup.enter="getResult()">Результат</button>
        </div>

        <div class="result-panel">
            <div class="result-adjacency-matrix">
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

            <div class="result-incidence-matrix">
                
            </div>
        </div>

    </div>
    
    <script src="/plagins/vue3.0/vue.js"></script>
    <script src="/plagins/axios/axios.js"></script>
    <script src="/script.js"></script>
</body>
</html>