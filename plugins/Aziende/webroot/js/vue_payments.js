var app = new Vue({
  el: "#app-payments",
  created: function () {
    $(document).on("loadPayments", function (event, triggeredId) {
      app.loadPayments(triggeredId);
      app.role = role;
    });
  },
  data() {
    return {
      modal: false,
      role: "",
      documents: [],
      document_types: [],
      payments: [],
      docType: null,
      payment: {
        statement_company_id: null,
        net_amount: null,
        oa_number_net: null,
        os_number_net: null,
        os_date_net: null,
        vat_amount: null,
        oa_number_vat: null,
        os_number_vat: null,
        os_date_vat: null,
        billing_reference: null,
        billing_date: null,
        protocol: null,
        cig: null,
        notes: null,
      },
      paymentForm: {
        net_amount: {
          attrs: {
            type: "number",
            step: 0.03,
            min: 0.01,
            required: true,
            name: "net_amount",
            id: "net_amount",
          },
          label: "Importo",
          valid: true,
          errors: "",
          rule: () => {
            this.greater("net_amount", 0);
          },
        },

        vat_amount: {
          label: "Importo",
          attrs: {
            type: "number",
            step: 0.03,
            min: 0.01,
            required: true,
            name: "vat_amount",
            id: "vat_amount",
          },
          valid: true,
          errors: "",
          rule: () => {
            this.greater("vat_amount", 0);
          },
        },

        billing_net_amount: {
          attrs: {
            type: "number",
            step: 0.03,
            min: 0.01,
            required: true,
            name: "billing_net_amount",
            id: "billing_net_amount",
          },
          label: "Importo (netto)",
          valid: true,
          errors: "",
          rule: () => {
            this.greater("billing_net_amount", 0);
          },
        },

        billing_vat_amount: {
          label: "Importo (IVA)",
          attrs: {
            type: "number",
            step: 0.03,
            min: 0.01,
            required: true,
            name: "billing_vat_amount",
            id: "billing_vat_amount",
          },
          valid: true,
          errors: "",
          rule: () => {
            this.greater("billing_vat_amount", 0);
          },
        },

        oa_number_net: {
          label: "N° OA",
          attrs: {
            type: "text",
            max: "16",
            required: true,
            name: "oa_number_net",
            id: "oa_number_net",
          },
          valid: true,
          rule: () => {
            this.max("oa_number_net", 16);
          },
        },

        os_number_net: {
          label: "N° OS",
          attrs: {
            type: "text",
            max: "16",
            required: true,
            name: "os_number_net",
            id: "os_number_net",
          },
          valid: true,
          rule: () => {
            this.max("os_number_net", 16);
          },
        },

        os_date_net: {
          label: "Data OS",
          attrs: {
            type: "date",
            required: true,
            name: "os_date_net",
            id: "os_date_net",
          },
          valid: true,
          rule: () => {
            this.validDate("os_date_net");
          },
        },
        oa_number_vat: {
          label: "N° OA",
          attrs: {
            type: "text",
            max: "16",
            required: true,
            name: "oa_number_vat",
            id: "oa_number_vat",
          },
          valid: true,
          rule: () => {
            this.max("oa_number_vat", 16);
          },
        },

        os_number_vat: {
          label: "N° OS",
          attrs: {
            type: "text",
            max: "16",
            required: true,
            name: "os_number_vat",
            id: "os_number_vat",
          },
          valid: true,
          rule: () => {
            this.max("os_number_vat", 16);
          },
        },

        os_date_vat: {
          label: "Data OS",
          attrs: {
            type: "date",
            required: true,
            name: "os_date_vat",
            id: "os_date_vat",
          },
          valid: true,
          rule: () => {
            this.validDate("os_date_vat");
          },
        },

        billing_reference: {
          label: "N° fattura",
          attrs: {
            type: "text",
            max: "16",
            required: true,
            name: "billing_reference",
            id: "billing_reference",
          },
          valid: true,
          rule: () => {
            this.max("billing_reference", 16);
          },
        },

        billing_date: {
          label: "Data fattura",
          attrs: {
            type: "date",
            required: true,
            name: "billing_date",
            id: "billing_date",
          },
          valid: true,
          rule: () => {
            this.validDate("billing_date");
          },
        },

        protocol: {
          label: "Protocollo",
          attrs: {
            type: "text",
            max: "16",
            required: false,
            name: "protocol",
            id: "protocol",
          },
          valid: true,
          rule: () => {
            this.max("protocol", 16);
          },
        },

        cig: {
          label: "CIG",
          attrs: {
            type: "text",
            max: "16",
            required: true,
            name: "cig",
            id: "cig",
          },
          valid: true,
          rule: () => {
            this.max("cig", 16);
            this.cig("cig");
          },
        },

        notes: {
          label: "Note di commento",
          attrs: {
            required: false,
            name: "notes",
            id: "notes",
          },
          valid: true,
        },
      },
      paymentFormErrors: [],
      modalClass: "",
      modalStyle: { display: "none" },
      statement_company_id: null,
    };
  },
  methods: {
    getSurveys() {
      axios
        .get(pathServer + `surveys/ws/getSurveysForPayments`)
        .then((res) => {
          if (res.data.response == "OK") {
            this.document_types = res.data.data.document_types;
          } else {
            alert(`Si è verificato un errore. ${res.data.msg}`);
          }
        })
        .catch((error) => alert(`Si è verificato un errore. ${error}`));
    },
    loadPayments(id) {
      this.statement_company_id = id;
      axios
        .get(
          pathServer + `aziende/payments/getPaymentsbyStatementCompany/${id}`
        )
        .then((res) => {
          if (res.data.response == "OK") {
            this.payments = res.data.data.payments;
          } else {
            alert(`Si è verificato un errore. ${res.data.msg}`);
          }
        })
        .catch((error) => alert(`Si è verificato un errore. ${error}`));
    },

    async loadPayment(id) {
      var result = false;
      await axios
        .get(pathServer + `aziende/payments/view/${id}`)
        .then((res) => {
          if (res.data.response == "OK") {
            this.payment = res.data.data.payment;
            this.payment.billing_date = moment(
              res.data.data.payment.billing_date
            ).format("yyyy-MM-DD");
            this.payment.os_date_net = moment(
              res.data.data.payment.os_date_net
            ).format("yyyy-MM-DD");
            this.payment.os_date_vat = moment(
              res.data.data.payment.os_date_vat
            ).format("yyyy-MM-DD");

            result = true;
          } else {
            alert(`Si è verificato un errore. ${res.data.msg}`);
          }
        })
        .catch((error) => {
          alert(`Si è verificato un errore. ${error}`);
        });
      return result;
    },
    async loadModal(id) {
      if (id) {
        let result = await this.loadPayment(id);
        if (result) {
          this.openModal();
        }
      } else {
        this.payment.statement_company_id = this.statement_company_id;
        this.payment.cig = cig;
        this.payment.billing_reference = billing_reference;
        this.payment.billing_net_amount = billing_net_amount;
        this.payment.billing_vat_amount = billing_vat_amount;

        this.payment.billing_date =
          billing_date === "Invalid date" ? null : billing_date;
        if (!!billing_reference) {
          this.payment.notes = `Fattura n° ${billing_reference}`;
          this.payment.notes += !!this.payment.billing_date
            ? ` del ${billing_date}`
            : "";
        }
        this.openModal();
      }
    },
    openModal() {
      this.modal = true;
      let bd = document.querySelector("body");
      bd.classList.add("modal-open");
      bd.style.paddingRight = "15px";
    },
    closeModal() {
      let bd = document.querySelector("body");
      bd.classList.remove("modal-open");
      bd.style.paddingRight = "";
      this.modal = false;
      Object.keys(this.payment).forEach((val) => (this.payment[val] = null));
    },
    validateForm() {
      this.paymentFormErrors = [];
      for (const [key, value] of Object.entries(this.paymentForm)) {
        // resetto la validità
        value.valid = true;

        if (value.attrs.required && !!this.payment[key] === false) {
          value.valid = false;
          this.paymentFormErrors.push(`Il campo ${value.label} è obbligatorio`);
        } else {
          if (Object.hasOwn(value, "rule")) {
            value.rule();
          }
        }
      }

      if (this.isFormValid) {
        this.submitPayment();
      } else {
        let errors = this.paymentFormErrors.join("\n");
        alert(errors);
      }
    },

    submitPayment() {
      let url = `${pathServer}aziende/payments`;
      if (this.payment.id) {
        url += `/edit/${this.payment.id}`;
        var method = "patch";
      } else {
        var method = "post";
        url += `/add`;
      }

      axios({
        method: method,
        url: url,
        data: { ...this.payment },
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((res) => {
          if (res.data.response == "OK") {
            if(this.payment.id) {
              this.payments = this.payments.map((val) => {
                if (val.id === res.data.data.payment.id) {
                  val = res.data.data.payment;
                }
                return val;
              });
            } else {
              this.payments.push(res.data.data.payment);
            }
            this.closeModal();
          } else {
            alert(`Si è verificato un errore. ${res.data.msg}`);
          }
        })
        .catch((error) => alert(`Si è verificato un errore. ${error}`));
    },

    deletePayment(id) {
      let check = confirm(
        "ATTENTIONE!\nOperazione irreversibile\nProcedere all'eliminazione?"
      );
      if (check) {
        axios({
          method: "delete",
          url: `${pathServer}aziende/payments/delete/${id}`,
        })
          .then((res) => {
            if (res.data.response == "OK") {
              this.payments = this.payments.filter((val) => val.id !== id);
              alert("Il pagamento è stato eliminato");
            } else {
              alert(`Si è verificato un errore. ${res.data.msg}`);
            }
          })
          .catch((error) => alert(`Si è verificato un errore. ${error}`));
      }
    },

    max(key, length) {
      if (this.payment[key]?.length > length) {
        this.paymentFormErrors.push(
          `Il campo ${this.paymentForm[key].label} può contenere massimo 16 caratteri.`
        );
        this.paymentForm[key].valid = false;
      }
    },

    greater(key, number) {
      if (this.payment[key]?.length < number) {
        this.paymentFormErrors.push(
          `Il campo ${this.paymentForm[key].label} deve essere maggiore di ${number}.`
        );
        this.paymentForm[key].valid = false;
      }
    },

    cig(key) {
      var regex = new RegExp(
        "[0-9]{7}[0-9A-F]{3}|[V-Z]{1}[0-9A-F]{9}|[A-U]{1}[0-9A-F]{9}"
      );
      if (!regex.test(this.payment[key])) {
        this.paymentFormErrors.push(
          `Il campo ${this.paymentForm[key].label} non è valido.`
        );
        this.paymentForm[key].valid = false;
      }
    },

    validDate(key) {
      let dt = new Date(this.payment[key]);
      if (dt.toDateString() === "Invalid Date") {
        this.paymentFormErrors.push(
          `Il campo ${this.paymentForm[key].label} non è una data valida.`
        );
        this.paymentForm[key].valid = false;
      }
    },
    downloadDocuments(stuff) {},

    generateDocs() {
      var formData = new FormData();
      formData.append("doc_type", this.docType);
      this.documents.forEach((val, index) =>
        formData.append(`documents[${index}]`, val)
      );

      axios
        .post(pathServer + `surveys/ws/create_interviews`, formData)
        .then((res) => {
          if (res.data.response == "OK") {
            res.data.data.documents.forEach((doc) => {
              this.payments.map((payment) => {
                if (doc.payment_id === payment.id) {
                  payment.documents.push(doc);
                }
              });
            });
          } else {
            alert(`Si è verificato un errore. ${res.data.msg}`);
          }
        })
        .catch((error) => alert(`Si è verificato un errore. ${error}`));
    },

    formatDate(rawDate) {
      return moment(rawDate).format(
        "DD/MM/YYYY"
      )
    },
    formatNumber(nr) {
      return new Intl.NumberFormat('it-IT', { style: 'currency', currency: 'EUR' }).format(nr);
    }
  },

  computed: {
    isFormValid() {
      return Object.keys(this.paymentForm).every(
        (val) => this.paymentForm[val].valid
      );
    },
    canWrite() {
      return this.role === "admin" || this.role === "ragioneria";
    },
  },
});
