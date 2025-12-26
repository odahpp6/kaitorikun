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
                gendarCheck:{class: 'animate-shadow-red', valid: false },
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
       // 1. 入力された値を取得
    let val = event.target.value;

    // 2. 全角数字を半角に変換（ここをより安全な正規表現に変更）
    val = val.replace(/[０-９]/g, (s) => {
        return String.fromCharCode(s.charCodeAt(0) - 65248);
    });

    // 3. 数字以外のすべての文字（ハイフン「-」「ー」、スペース、記号など）を削除
    const tel = val.replace(/[^0-9]/g, '');

    // 4. 入力欄の値を上書き（ハイフンなどが消えた数字だけの状態になる）
    event.target.value = tel;

    // 5. バリデーション判定
    const isEmpty = tel === "";
    const isValid = !isEmpty && tel.length >= 10; 

    this.message.tel.class = isValid ? '' : 'animate-shadow-red';
    this.message.tel.valid = isValid;
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
        },
        checkGender(event) {
            const value = event.target.value;
            this.message.gendarCheck.valid = value === 'male' || value === 'female';
            this.message.gendarCheck.class = this.message.gendarCheck.valid ? '' : 'animate-shadow-red';
        }
        

    }
});

app.mount('#app_mount');
