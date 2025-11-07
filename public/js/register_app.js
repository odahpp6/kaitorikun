
    const app = Vue.createApp({
        data() {
            return {
                // データプロパティをここに追加
                message: {
                    password: { text: '', class: 'bg-red-100',valid: false },
                    email: { text: '', class: 'bg-red-100',valid: false },
                    company: { text: '', class: 'bg-red-100',valid: false},
                    buttunClass: ''
                }
            };
        },
        methods: {
            // メソッドをここに追加
            checkPassword(event) {
                const password = event.target.value;
                if (password === "") {
                    this.message.password.text = "passwordを入力してください";
                    this.message.password.class='bg-red-100 border-red-500';
                } else if (password.length < 8) {
                    this.message.password.text = "passwordは8文字以上で入力してください";
                     this.message.password.class='bg-red-100 border-red-500';
                } else if (password.length > 20) {
                    this.message.password.text = "passwordは20文字以下で入力してください";
                     this.message.password.class='bg-red-100 border-red-500';
                } else {
                    this.message.password.text = "";
                    this.message.password.class='bg-white border-none';
                    this.message.password.valid = true;
                }
                this.compleate(); // ✅ 呼び出し追加
            },
            checkEmail(event){
                const email = event.target.value;
                if(email ===""){
                    this.message.email.text="emailを入力してください";
                    this.message.email.class='bg-red-100 border-red-500';
                }else if(!email.includes("@")){
                    this.message.email.text="emailの形式が不正です";
                    this.message.email.class='bg-red-100 border-red-500';
                }else{
                    this.message.email.text="";
                    this.message.email.class='bg-white border-none';
                    this.message.email.valid = true;
                }
                this.compleate(); // ✅ 呼び出し追加

            },
            checkCompany(event){
                const company = event.target.value; 
                if(company ===""){
                     this.message.company.text="会社名を入力してください";
                     this.message.company.class='bg-red-100 border-red-500';
                }else{
                    this.message.company.text="";
                    this.message.company.class='bg-white border-none';
                    this.message.company.valid = true;
                }
                this.compleate(); // ✅ 呼び出し追加
            },
            compleate() {
                if(this.message.password.valid && this.message.email.valid && this.message.company.valid){
                     this.message.buttunClass='bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 cursor-pointer transition-colors duration-200';
                }else{
                    this.message.buttunClass='';
                }

            },   
            handleSubmit() {
                // フォームの送信処理
                if(this.message.password.valid && this.message.email.valid && this.message.company.valid){

                    // フォームを送信
                    this.$refs.formEl.submit();
                }else{
                    alert("必須項目を正しく入力してください");
                }
            }
        }
    });
    app.mount('#main');
