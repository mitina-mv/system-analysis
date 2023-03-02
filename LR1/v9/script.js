const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            arrData: [],
            incidenceMatrix: [],
            incidenceLeft: [],
            innerDataCorrect: [],
            flag: false, 
            errorText: null
        }
    },

    methods: {
        addDataInput: function() 
        {
            if(this.countVertex < this.arrData.length){
                this.arrData = this.arrData.slice(0, this.countVertex);
            }

            for(let i = 0; i < this.countVertex; ++i)
            {
                if(typeof this.arrData[i] == 'undefined'){
                    this.arrData.push('');
                }
            }
            this.flag = false;
        },

        getResult: function()
        {
            let tmpData;
            let flagIncorrectData = false;
            this.errorText = null;
            this.incidenceMatrix = [];
            this.incidenceLeft = [];
            this.innerDataCorrect = [];

            for(let i = 0; i < this.arrData.length; ++i)
            {
                tmpData = this.arrData[i].split(', ');

                if(this.arrData[i] == '') continue;

                tmpData.forEach(j => {
                    if(isNaN(j) || (j <= 0 || j > this.countVertex)) {
                        this.errorText = `В поле G(${i+1}) введено некорректное значение!`;
                        flagIncorrectData = true;
                        return;
                    }

                    this.incidenceMatrix[`${i + 1}-${j}`] = {};

                    for(let k = 1; k <= this.countVertex; ++k)
                    {
                        this.incidenceMatrix[`${i + 1}-${j}`][k] = 0;
                    }

                    if(j == (i + 1)){
                        this.incidenceMatrix[`${i + 1}-${j}`][j] = 2;
                    } else {
                        this.incidenceMatrix[`${i + 1}-${j}`][j] = -1;
                        this.incidenceMatrix[`${i + 1}-${j}`][i + 1] = 1;
                    }
                });

                if(flagIncorrectData) {
                    this.flag = false;
                    break;
                }

                this.innerDataCorrect[i] = tmpData;
                this.flag = true;
            }
            
            if(this.flag) 
            {
                for(let i = 0; i < this.countVertex; ++i)
                {
                    this.incidenceLeft.push([]);
                }

                // по обработанному множеству правых инциденций собираем мн-во левых инц.
                this.innerDataCorrect.forEach((arr, index) => {
                    arr.forEach((item) => {
                        this.incidenceLeft[item - 1].push(index);
                    })
                })

                // сортировка для ребер
                this.incidenceMatrix = Object.keys(this.incidenceMatrix).sort().reduce(
                    (obj, key) => {
                      obj[key] = this.incidenceMatrix[key];
                      return obj;
                    },
                    {}
                );
            }
        },
        getString: function(arr) 
        {
            return arr.map(v => Number(v) + 1).join(', ');
        },
        remove()
        {
            this.countVertex = 0;
            this.flag = false;
            this.arrData = [];
            this.errorText = null;
        }
    }
})

app.mount("#app")