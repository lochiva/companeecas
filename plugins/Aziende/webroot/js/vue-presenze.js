var app = new Vue({
    el: '#app-presenze',
    data: {
		sede_id: '',
        date: new Date(),
        guests: [],
        infoGuest: {
            check_in_date: '',
            cui: '',
            vestanet_id: '',
            name: '',
            surname: '',
            birthdate: '',
            country_birth_name: '',
            sex: '',
            minor: ''
        },
        check_all_guests: false,
        count_presenze_day: 0,
        count_presenze_month: 0,
        next_sede: next_sede,
        datepickerItalian: vdp_translation_it.js
    },

    components: {
        'datepicker': vuejsDatepicker,
    },

    computed: {
        noNextSedeMessage() {
            return this.next_sede ? '' : "Questa è l'ultima struttura";
        }
    },
      
    mounted: function () {

        var url = new URL(window.location.href);
        this.sede_id = url.searchParams.get("sede");

        this.loadGuests();
    },
       
    methods: {

        changedDate() {
            var today = new Date();
            if (this.date > today) {
                this.$refs.inputDate.selectDate({timestamp: today.getTime()});
            } else {
                this.loadGuests();
            }
        },

        loadGuests () {
			var formatted_date = moment(this.date).format('YYYY-MM-DD');
            axios.get(pathServer + 'aziende/ws/getGuestsForPresenze?sede='+this.sede_id+'&date='+formatted_date)
                .then(res => {  
                    if (res.data.response == 'OK') { 
						this.guests = res.data.data.guests;
                        this.count_presenze_day = res.data.data.count_presenze_day;
                        this.count_presenze_month = res.data.data.count_presenze_month;

                        if (this.guests.length > 0) {
                            this.check_all_guests = true;
                            this.guests.forEach((guest) => {
                                if (!guest.presente) {
                                    this.check_all_guests = false;
                                    return false;
                                }
                            });
                        } else {
                            this.check_all_guests = false;
                        }
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        save (next) {
            let params = new URLSearchParams();

			params.append('sede', this.sede_id);
			params.append('date', moment(this.date).format('YYYY-MM-DD'));
            params.append('guests', JSON.stringify(this.guests));

            axios.post(pathServer + 'aziende/ws/saveGuestsPresenze', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        if (next) {
                            window.location = pathServer + 'aziende/sedi/presenze?sede=' + this.next_sede;
                        } else {
                            this.guests = res.data.data.guests;
                            this.count_presenze_day = res.data.data.count_presenze_day;
                            this.count_presenze_month = res.data.data.count_presenze_month;
                        }
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });

        },

        checkAllGuests () {
            if (this.check_all_guests) {
                this.guests.forEach(function(guest) {
                    guest.presente = true;
                });
            } else {
                this.guests.forEach(function(guest) {
                    guest.presente = false;
                });
            }
        },

        openModalInfoGuest (guest) {
            this.infoGuest = guest;
            let modalGuestInfo = this.$refs.modalGuestInfo;
            $(modalGuestInfo).modal('show');
        }
        
    }

});