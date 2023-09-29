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
      role: "",
      documents: [],
      document_types: [],
      payments: [],
      docType: null,
      payment: {
        statement_company_id: null,
        net_amount: null,
        oa_number: null,
        os_number: null,
        os_date: null,
        billing_reference: null,
        billing_date: null,
        protocol: null,
        cig: null,
        notes: null,
      },
      paymentForm: {
        net_amount: {
          label: "Importo",
          type: "number",
          step: 0.03,
          min: 0.01,
          required: true,
          valid: true,
          errors: "",
          rule: () => {
            if (this.payment.net_amount < 0) {
              let err = `Il campo ${this.paymentForm.net_amount.label} deve essere maggiore di 0.`;
              this.paymentFormErrors.push(err);
              this.paymentForm.net_amount.valid = false;
            }
          },
        },

        oa_number: {
          label: "N° OA",
          type: "text",
          max: "16",
          required: true,
          valid: true,
          rule: () => {
            this.max("oa_number", 16);
          },
        },

        os_number: {
          label: "N° OS",
          type: "text",
          max: "16",
          required: true,
          valid: true,
          rule: () => {
            this.max("os_number", 16);
          },
        },

        os_date: {
          label: "Data OS",
          type: "date",
          required: true,
          valid: true,
          rule: () => {
            this.validDate("os_date");
          },
        },

        billing_reference: {
          label: "N° fattura",
          type: "text",
          max: "16",
          required: true,
          valid: true,
          rule: () => {
            this.max("billing_reference", 16);
          },
        },

        billing_date: {
          label: "Data fattura",
          type: "date",
          required: true,
          valid: true,
          rule: () => {
            this.validDate("billing_date");
          },
        },

        protocol: {
          label: "Protocollo",
          type: "text",
          max: "16",
          required: true,
          valid: true,
          rule: () => {
            this.max("protocol", 16);
          },
        },

        cig: {
          label: "CIG",
          type: "text",
          max: "16",
          required: true,
          valid: true,
          rule: () => {
            this.max("cig", 16);
            this.cig("cig");
          },
        },

        notes: {
          label: "Note di commento",
          type: "textarea",
          required: false,
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
            this.payments = res.data.data.payments.map((payment) => {
              payment.billing_date = moment(payment.billing_date).format(
                "DD/MM/YYYY"
              );
              payment.os_date = moment(payment.os_date).format("DD/MM/YYYY");
              return payment;
            });
          } else {
            alert(`Si è verificato un errore. ${res.data.msg}`);
          }
        })
        .catch((error) => alert(`Si è verificato un errore. ${error}`));
    },
    openModal(id) {
      this.modalClass = "in";
      this.modalStyle.display = "block";
      this.payment.statement_company_id = this.statement_company_id;
      this.payment.cig = cig;
      this.payment.billing_reference = billing_reference;

      this.payment.billing_date =
        billing_date === "Invalid date" ? null : billing_date;
      if (!!billing_reference) {
        this.payment.notes = `Fattura n° ${billing_reference}`;
        this.payment.notes += !!this.payment.billing_date
          ? ` del ${billing_date}`
          : "";
      }
    },
    closeModal() {
      this.modalClass = "";
      this.modalStyle.display = "none";
      Object.keys(this.payment).forEach((val) => (this.payment[val] = null));
    },
    validateForm() {
      this.paymentFormErrors = [];
      for (const [key, value] of Object.entries(this.paymentForm)) {
        // resetto la validità
        value.valid = true;

        if (value.required && !!this.payment[key] === false) {
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
      var formData = new FormData();
      for (const [key, value] of Object.entries(this.payment)) {
        formData.append(key, value);
      }
      axios
        .post(pathServer + `aziende/payments/add`, formData)
        .then((res) => {
          if (res.data.response == "OK") {
            this.payments.push(res.data.data.payment);
            this.closeModal();
          } else {
            alert(`Si è verificato un errore. ${res.data.msg}`);
          }
        })
        .catch((error) => alert(`Si è verificato un errore. ${error}`));
    },

    max(key, length) {
      if (this.payment[key].length > length) {
        this.paymentFormErrors.push(
          `Il campo ${this.paymentForm[key].label} può contenere massimo 16 caratteri.`
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
      // formData.append('documents', JSON.stringify(this.documents));
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
  },

  computed: {
    isFormValid() {
      return Object.keys(this.paymentForm).every(
        (val) => this.paymentForm[val].valid
      );
    },
  },
});
