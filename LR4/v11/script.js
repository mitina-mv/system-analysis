const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            arrData: [],
            flag: false,
            dataGraph: null,
            matrixPath: []
        }
    },

    methods: {
        addDataInput: function() {
            this.arrData = [];
            
            for(let i = 0; i < this.countVertex; ++i)
            {
                this.arrData.push([]);
                for(let j = 0; j < this.countVertex; ++j)
                {
                    this.arrData[i][j] = 0;
                }
            }

            this.flag = false;
        },
        getResult: function()
        {
            this.flag = true;
            this.tree = [];
            this.matrixPath = [];

            for(let i = 0; i < this.countVertex; ++i)
            {
                for(let j = 0; j < this.countVertex; ++j)
                {
                    if(this.arrData[i][j] < 0){
                        this.flag = false;
                        break;
                    }
                }

                if(!this.flag) break;
            }

            if(this.flag)
            {                
                axios
                    .post('./getResult.php', {
                        graph: this.arrData
                    }
                    )
                    .then(response => {
                        this.dataGraph = response.data.data;
                        this.matrixPath = response.data.matrix;
                    })
                    .catch(error => console.log(error));
            }
        },
        getString: function(arr) 
        {
            return arr.map(v => Number(v) + 1).join(' -> ');
        },
        remove(){
            this.countVertex = 0;
            this.flag = false;
            this.arrData = [];
        }
    }
})

app.mount("#app")