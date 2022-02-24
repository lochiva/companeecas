var app = new Vue({
    el: '#app-presenze',
    data: {
		sede_id: '',
        date: new Date(),
        guests: [],
        datepickerItalian: vdp_translation_it.js
    },

    components: {
        'datepicker': vuejsDatepicker,
    },

    computed: {

    },
      
    mounted: function () {

        var url = new URL(window.location.href);
        this.sede_id = url.searchParams.get("sede");

        this.loadGuests();
    },
       
    methods: {

        loadGuests () {
			var formatted_date = moment(this.date).format('YYYY-MM-DD');
            axios.get(pathServer + 'aziende/ws/getGuestsForPresenze?sede='+this.sede_id+'&date='+formatted_date)
                .then(res => {  
                    if (res.data.response == 'OK') { 
						this.guests = res.data.data;
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        save () {
            let params = new URLSearchParams();

			params.append('sede', this.sede_id);
			params.append('date', moment(this.date).format('YYYY-MM-DD'));
            params.append('guests', JSON.stringify(this.guests));

            axios.post(pathServer + 'aziende/ws/saveGuestsPresenze', params)
                .then(res => {
                    if (res.data.response == 'OK') {
						this.guests = res.data.data;
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });

        }
        
    }

});