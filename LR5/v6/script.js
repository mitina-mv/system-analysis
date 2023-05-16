const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            countM: 0,
            namesVertex: [],
            arrData: [],
            matrixA: [],
            matrixD: [],
            zi: [],
            b: null,
            zmax: null,
            q: null,
            flag: false, 
            errorText: null
        }
    },

    methods: {
        addDataInput: function() 
        {
            if(this.countVertex == 0 || this.countM == 0) return;
            
            this.arrData = [];
            
            for(let i = 0; i < this.countM; ++i)
            {
                this.arrData.push([]);
                for(let j = 0; j < this.countVertex; ++j)
                {
                    this.arrData[i][j] = 0;
                    this.namesVertex.push(j + 1);
                }
            }

            this.flag = false;
        },

        getResult: function()
        {
            let flagIncorrectData = true;
            // получение входных данных
            for(let i = 0; i < this.arrData.length; ++i)
            {                
                for(let j = 0; j < this.countVertex; ++j)
                {
                    if(this.arrData[i][j] != -1
                    && this.arrData[i][j] != 1
                    && this.arrData[i][j] != 0
                    ) {
                        flagIncorrectData = false;
                        break;
                    }
                    
                }

                if(!flagIncorrectData) {
                    this.flag = false;
                    break;
                }

                this.flag = true;
            }

            if(this.flag)
            {                
                axios
                    .post('./getResult.php', this.arrData)
                    .then(response => {
                        if(!response.data.status)
                        {
                            this.matrixA = response.data.matrixA
                            this.matrixD = response.data.matrixD
                            this.zi = response.data.zi
                            this.b = response.data.b
                            this.zmax = response.data.zmax
                            this.q = response.data.q
                        } else {
                            this.errorText = response.data.message;
                            console.log(this.errorText);
                            this.flag = false;
                        }
                    })
                    .catch(error => {
                        this.errorText = `Не удалось обработать запрос`;
                        console.log(error)
                        this.flag = false;
                    });
            }
        },
        getString: function(arr, d = 1) 
        {
            return arr.map(v => Number(v) + d).join(', ');
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