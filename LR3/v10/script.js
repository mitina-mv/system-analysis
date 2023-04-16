const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            arrData: [],
            matrix: [],
            edges: [],
            graphs: [],
            flag: false
        }
    },

    methods: {
        addDataInput: function() {
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
            let tmpData = {};
            let flagIncorrectData = true;

            this.matrix = [];
            this.edges = [];
            this.graphs = [];

            // получение входных данных
            for(let i = 0; i < this.arrData.length; ++i)
            {                
                tmpData[i] = this.arrData[i].split(', ');

                if(this.arrData[i] == '')
                {
                    tmpData[i] = [];
                    continue;
                } 

                flagIncorrectData = tmpData[i].every(j => {
                    if(isNaN(j) || (Number(j) <= 0 || Number(j) > this.countVertex))
                    {
                        return false;
                    } 
                    else return true;
                });

                if(!flagIncorrectData) {
                    this.flag = false;
                    break;
                }

                this.flag = true;
            }

            if(this.flag)
            {                
                axios
                    .post('./getResult.php', tmpData)
                    .then(response => {
                        this.matrix = response.data.matrix
                        this.edges = response.data.edges
                        this.graphs = response.data.graphs
                    })
                    .catch(error => console.log(error));
            }
        },
        getString: function(arr, d = 1) 
        {
            return arr.map(v => Number(v) + d).join(', ');
        },
        remove(){
            this.countVertex = 0;
            this.flag = false;
            this.arrData = [];
        }
    }
})

app.mount("#app")