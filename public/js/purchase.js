// Purchase page scripts (placeholder).

const app = Vue.createApp({
    data() {
        return {
            // データプロパティをここに追加
            message: {
                name: { class: 'animate-shadow-red', valid: false },
                tel: { class: 'animate-shadow-red', valid: false },
                proof_type: { valid: false,touched: true },
                proof_img_1: { class: 'animate-shadow-red' },
                occupation: { valid: false,touched: true },
                prefecture: { valid: false, touched: true },
                city: { valid: false, touched: true },
                address_detail: { valid: false, touched: true },
                buttunClass: ''
            }
        };
    },
    methods: {
        checkProofType(event) {
            const value = event.target.value;
            this.message.proof_type.touched = true;
            this.message.proof_type.valid = value !== '';
        },  
        checkName(event) {
            const name = event.target.value.trim();
            const isEmpty = name === "";
            this.message.name.class = isEmpty ? 'animate-shadow-red' : '';
            this.message.name.valid = !isEmpty;
        },
        checkTel(event) {
            const tel = event.target.value.trim();
            const isEmpty = tel === "";
            this.message.tel.class = isEmpty ? 'animate-shadow-red' : '';
            this.message.tel.valid = !isEmpty;
        },
        checkProofImage(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            if (file) {
                this.message.proof_img_1.class = '';
            } else {
                this.message.proof_img_1.class = 'animate-shadow-red';
            }
        },
        checkOccupation(event) {
            const value = event.target.value;
            this.message.occupation.touched = true;
            this.message.occupation.valid = value !== '';
        },
        checkPrefecture(event) {
            const value = event.target.value;
            this.message.prefecture.touched = true;
            this.message.prefecture.valid = value !== '';
        },
        checkCity(event) {
            const value = event.target.value.trim();
            this.message.city.touched = true;
            this.message.city.valid = value !== '';
        },
        checkAddressDetail(event) {
            const value = event.target.value.trim();
            this.message.address_detail.touched = true;
            this.message.address_detail.valid = value !== '';
        }
        

    }
});

app.mount('#app_mount');
