var app = new Vue({
    el: '#app-presenze',
    data: {
		sede_id: '',
        role: role,
        now: new Date(),
        date: new Date(),
        guests: [],
        file: null,
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
        datepickerItalian: vdp_translation_it.js,
        fileUploaded: {
            date: null,
            sede_id: null
        },
        fileCheck: null
    },

    components: {
        'datepicker': vuejsDatepicker,
    },

    computed: {
        saveDisabled() {
            var date = moment(this.date).format('YYYY-MM-DD');
            var yesterday = moment(this.now).subtract(1, 'days').format('YYYY-MM-DD');
            var nowTime = moment(this.now).format('HH:mm');
            return this.role == 'ente_contabile' || (
                    this.role == 'ente_ospiti' && 
                    (date < yesterday || date == yesterday && nowTime > '12:00')
                );
        },
        noNextSedeMessage() {
            return this.next_sede ? '' : "Questa è l'ultima struttura";
        },
        saveDisabledPastDaysMessage() {
            return this.saveDisabled ? 'Le presenze vanno comunicate il giorno stesso ed al più tardi entro le ore 12 del giorno successivo. Variazioni rispetto alle presenze nei giorni passati vanno richieste.' : '';
        }
    },
      
    mounted: function () {

        var url = new URL(window.location.href);
        this.sede_id = url.searchParams.get("sede");

        this.fileUploaded.date = moment(this.date).format('YYYY-MM-DD');
        this.fileUploaded.sede_id = this.sede_id;

        this.loadGuests();
        this.loadFiles();
    },
       
    methods: {

        changedDate() {
            if (this.date > this.now) {
                this.$refs.inputDate.selectDate({timestamp: this.now.getTime()});
            } else {
                this.updateTime();
                this.loadGuests();
                this.loadFiles();
            }
            this.fileUploaded.date = moment(this.date).format('YYYY-MM-DD');
        },

        updateTime() {
            axios.get(pathServer + 'ws/getCurrentTime')
            .then(res => {  
                if (res.data.response == 'OK') { 
                    this.now = new Date(res.data.data);
                } else {
                    alert(res.data.msg);
                }
            }).catch(error => {
                console.log(error);
            });
        },

        loadGuests () {
            this.guests = [];
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

        loadFiles () {
            let params = new URLSearchParams();
            params.append('data', moment(this.date).format('YYYY-MM-DD'));
            axios.post(pathServer + 'aziende/ws/getFiles/' + this.sede_id, params)
                .then(res => {  
                    if (res.data.response == 'OK') { 
						this.file = res.data.data;
                    } else {
                        this.file = null;
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        save (next) {
            let params = new URLSearchParams();

			params.append('sede', this.sede_id);
			params.append('date', moment(this.date).format('YYYY-MM-DD'));

            let guestsData = [];
            this.guests.forEach((guest) => {
                guestsData.push({
                    guest_id: guest.id,
                    presenza_id: guest.id_presenza,
                    presente: guest.presente,
                    note: guest.note
                })
            });
            params.append('guests', JSON.stringify(guestsData));

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

        next () {
            window.location = pathServer + 'aziende/sedi/presenze?sede=' + this.next_sede;
        },

        checkAllGuests () {
            if (this.check_all_guests) {
                this.guests.forEach(function(guest) {
                    if (!guest.suspended) {
                        guest.presente = true;
                    }
                });
            } else {
                this.guests.forEach(function(guest) {
                    if (!guest.suspended) {
                        guest.presente = false;
                    }
                });
            }
        },

        openModalInfoGuest (guest) {
            this.infoGuest = guest;
            let modalGuestInfo = this.$refs.modalGuestInfo;
            $(modalGuestInfo).modal('show');
        },

        deleteFile(file) {
            file.deleted = 1; 

            axios.post(pathServer + 'aziende/ws/deleteFile/' + this.file.id)
                .then(res => {
                    if (res.data.response == 'OK') {
                        this.file = null;
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });

        },

        submitFile() {
            let attachment = this.$refs.attachment.files[0];

            if(attachment.type == 'application/pdf') {
                let formData = new FormData();
                formData.append('file', JSON.stringify(this.fileUploaded));
                formData.append('attachment', (attachment));
    
                let headers = { 'Content-Type': 'multipart/form-data' };
    
                axios.post(pathServer + 'aziende/ws/saveFiles', formData)
                .then(res => {
                    this.$refs.attachment.files[0] = null;
                    this.fileCheck = null;
                    
                    if (res.data.response == 'KO') {
                        alert(res.data.msg);
                    } else {
                        this.file = res.data.data;
                    }
                })
                .catch(error => {
                    alert(error);
                });

            } else {
                alert('Il formato del file selezionato non è supportato');
                this.$refs.attachment.files[0] = null;
                this.fileCheck = null;

            }
        }
        
    }

});