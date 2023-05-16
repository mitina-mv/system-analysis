const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            countM: 0,
            namesEdges: [],
            arrData: [],
            matrixA: [],
            stepeniVartex: [],
            r: null,
            eps: null,
            messR: null,
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
                    this.namesEdges.push(j + 1);
                }
            }

            this.flag = true;
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
                            this.stepeniVartex = response.data.stepeniVartex
                            this.r = response.data.r
                            this.eps = response.data.eps
                            this.messR = response.data.messR
                        } else {
                            this.errorText = response.data.message;
                            console.log(this.errorText);
                            this.flag = false;
                        }
                    })
                    .catch(error => {
                        this.errorText = `Не удалось обработать запрос`;
                        console.log(error)
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