@extends('layouts.member')

@section('title', '見積登録')
@section('content')

<div id="tanka">
<div v-show="Visible">
  
<h1 class="text-center noprint text-2xl font-bold my-4">田中貴金属の本日の金買取金額</h1>
<p class="text-red-600 noprint mb-2">※1日のはじめに必ずF5の更新をしてから利用ください。</p>

<div class="noprint">
  <p class="mb-2">参照：<a href="https://gold.tanaka.co.jp/retanaka/price/" target="_blank" class="text-blue-600 underline">https://gold.tanaka.co.jp/retanaka/price/</a></p>
  <div class="flex flex-col md:flex-row gap-4 mb-4">
    <div class="md:w-1/3">
      <table class="w-full border border-gray-300 rounded-lg overflow-hidden mb-4">
        <thead>
          <tr class="bg-gray-100">
            <th class="border px-3 py-2">金の種類</th>
            <th class="border px-3 py-2">買取価格</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border px-3 py-2">K24特定</td>
            <td class="border px-3 py-2">{{ $prices['k24tokutei'] }}</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">K24</td>
            <td class="border px-3 py-2">{{ $prices['k24'] }}</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">K22</td>
            <td class="border px-3 py-2">{{ $prices['k22'] }}</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">K20</td>
            <td class="border px-3 py-2">{{ $prices['k20'] }}</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">K18</td>
            <td class="border px-3 py-2">{{ $prices['k18'] }}</td>
          </tr>
        </tbody>
            </table>
          </div>
          <div class="md:w-1/3">
            <table class="w-full border border-gray-300 rounded-lg overflow-hidden mb-4">
        <thead>
          <tr class="bg-gray-100">
            <th class="border px-3 py-2">金の種類</th>
            <th class="border px-3 py-2">買取価格</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border px-3 py-2">K16</td>
            <td class="border px-3 py-2">{{ $prices['k16'] }}</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">K14</td>
            <td class="border px-3 py-2">{{ $prices['k14'] }}</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">K12</td>
            <td class="border px-3 py-2">{{ $prices['k12'] }}</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">K10</td>
            <td class="border px-3 py-2">{{ $prices['k10'] }}</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">K9</td>
            <td class="border px-3 py-2">{{ $prices['k9'] }}</td>
          </tr>
        </tbody>
            </table>
          </div>
          <div class="md:w-1/3">
      <table class="w-full border border-gray-300 rounded-lg overflow-hidden mb-4">
        <thead>
          <tr class="bg-gray-100">
        <th class="border px-3 py-2">プラチナの種類</th>
        <th class="border px-3 py-2">買取価格</th>
          </tr>
        </thead>
        <tbody>
          <tr>
        <td class="border px-3 py-2">PT特定</td>
        <td class="border px-3 py-2">{{ $prices['pttokutei'] }}</td>
          </tr>
          <tr>
        <td class="border px-3 py-2">PT1000</td>
        <td class="border px-3 py-2">{{ $prices['pt1000'] }}</td>
          </tr>
          <tr>
        <td class="border px-3 py-2">PT950</td>
        <td class="border px-3 py-2">{{ $prices['pt950'] }}</td>
          </tr>
          <tr>
        <td class="border px-3 py-2">PT900</td>
        <td class="border px-3 py-2">{{ $prices['pt900'] }}</td>
          </tr>
          <tr>
        <td class="border px-3 py-2">PT850</td>
        <td class="border px-3 py-2">{{ $prices['pt850'] }}</td>
          </tr>
          <tr>
        <td class="border px-3 py-2">PT800</td>
        <td class="border px-3 py-2">{{ $prices['pt800'] }}</td>
          </tr>
          <tr>
        <td class="border px-3 py-2">PT750</td>
        <td class="border px-3 py-2">{{ $prices['pt750'] }}</td>
          </tr>
          <tr>
        <td class="border px-3 py-2">silver(銀すべて)</td>
        <td class="border px-3 py-2">{{ $prices['silver'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div><button @click="tanaka" class="bg-blue-500 rounded text-white px-3 py-2 mb-4 text-xs"> @{{ Visible ? '田中貴金属の料金を非表示にする' : '田中貴金属の料金を表示する' }}</button>
</div>




<div id="app" class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">

<form action="{{ route('estimate.update', $Estimate->id) }}" method="POST">

@csrf
@method('PATCH')


<input type="text" name="title" v-model="title" :min="0" class="w-50 border rounded px-2 py-1 focus:outline-none focus:ring focus:border-blue-300" />

  <table class="w-full border border-gray-300 text-sm mb-4">
    <thead>
    <tr class="bg-gray-100">
      <th class="border px-2 py-2">買取品目</th>
      <th class="border px-2 py-2">査定価格</th>
      <th class="border px-2 py-2">数量</th>
      <th class="border px-2 py-2 total">合計</th>
      <th class="border px-2 py-2">備考</th>
      <th class="border px-2 py-2">削除</th>
    </tr>
    </thead>
    <tbody>

      

    <tr class="hover:bg-gray-50" v-for="(row, index) in rows" :key="index">
      <td class="border px-2 py-1">
      <input type="text" v-model="row.text" :name="'text[' + index + ']'" class="w-full border rounded px-2 py-1 focus:outline-none focus:ring focus:border-blue-300" />
      </td>
      <td class="border px-2 py-1">
      <input type="number" v-model.number="row.num1" :name="'num1[' + index + ']'" :min="0" class="w-20 border rounded px-2 py-1 focus:outline-none focus:ring focus:border-blue-300" />
      </td>
      <td class="border px-2 py-1">
      <input type="number" v-model.number="row.num2" :name="'num2[' + index + ']'" :min="1" class="w-20 border rounded px-2 py-1 focus:outline-none focus:ring focus:border-blue-300" />
      </td>
      <td class="border px-2 py-1 text-right">@{{ formatPrice(rowcalc(row)) }}</td>
      <td class="border px-2 py-1">
      <textarea v-model="row.remarks" :name="'remarks[' + index + ']'" class="w-full border rounded px-2 py-1 mb-2 focus:outline-none focus:ring focus:border-blue-300" placeholder="備考を入力してください" rows="2"></textarea>
      
      <a @click="gold_calc_show(index)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded mb-2 text-xs">
        @{{ row.showGoldCalcForRow ? '計算フォームを閉じる' : '金相場を計算する' }}
      </a>

            <!-- モーダルウィンドウ -->
            <div v-if="row.showGoldCalcForRow" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
              <div class="gold_calc bg-white p-4 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                  <h3 class="text-xl font-semibold">金相場計算</h3>
                  <a @click="gold_calc_show(index)" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                  <div class="flex items-center gap-2">
                    <h4 class="font-semibold whitespace-nowrap">①基本:</h4>
                    <select v-model="row.gold1" :name="'goldlist[' + index + ']'" class="w-full border rounded px-2 py-1">
                        <option value="{{ $prices['k24tokutei'] }}">k24特定 ({{ number_format($prices['k24tokutei']) }})</option>
                        <option value="{{ $prices['k24'] }}">k24 ({{ number_format($prices['k24']) }})</option>
                        <option value="{{ $prices['k22'] }}">k22 ({{ number_format($prices['k22']) }})</option>
                        <option value="{{ $prices['k20'] }}">k20 ({{ number_format($prices['k20']) }})</option>
                        <option value="{{ $prices['k18'] }}">k18 ({{ number_format($prices['k18']) }})</option>
                        <option value="{{ $prices['k16'] }}">k16 ({{ number_format($prices['k16']) }})</option>
                        <option value="{{ $prices['k14'] }}">k14 ({{ number_format($prices['k14']) }})</option>
                        <option value="{{ $prices['k12'] }}">k12 ({{ number_format($prices['k12']) }})</option>
                        <option value="{{ $prices['k10'] }}">k10 ({{ number_format($prices['k10']) }})</option>
                        <option value="{{ $prices['k9'] }}">k9 ({{ number_format($prices['k9']) }})</option>
                        <option value="{{ $prices['pttokutei'] }}">PT特定 ({{ number_format($prices['pttokutei']) }})</option>
                        <option value="{{ $prices['pt1000'] }}">PT1000 ({{ number_format($prices['pt1000']) }})</option>
                        <option value="{{ $prices['pt950'] }}">PT950 ({{ number_format($prices['pt950']) }})</option>
                        <option value="{{ $prices['pt900'] }}">PT900 ({{ number_format($prices['pt900']) }})</option>
                        <option value="{{ $prices['pt850'] }}">PT850 ({{ number_format($prices['pt850']) }})</option>
                        <option value="{{ $prices['pt800'] }}">PT800 ({{ number_format($prices['pt800']) }})</option>
                        <option value="{{ $prices['pt750'] }}">PT750 ({{ number_format($prices['pt750']) }})</option>
                        <option value="{{ $prices['silver'] }}">silver ({{ number_format($prices['silver']) }})</option>
                    </select>
                    
                  </div>
                  <div class="flex items-center gap-2">
                    <h4 class="text-sm whitespace-nowrap">②<span class="text-xs text-gray-400">(コンビ):</span></h4>
                    <select v-model="row.gold2" :name="'goldlist2[' + index + ']'" class="w-full border rounded px-2 py-1">
                      <option value="0">コンビ無し</option>
                      <option value="{{ $prices['k24tokutei'] }}">k24特定 ({{ number_format($prices['k24tokutei']) }})</option>
                      <option value="{{ $prices['k24'] }}">k24 ({{ number_format($prices['k24']) }})</option>
                      <option value="{{ $prices['k22'] }}">k22 ({{ number_format($prices['k22']) }})</option>
                      <option value="{{ $prices['k20'] }}">k20 ({{ number_format($prices['k20']) }})</option>
                      <option value="{{ $prices['k18'] }}">k18 ({{ number_format($prices['k18']) }})</option>
                      <option value="{{ $prices['k16'] }}">k16 ({{ number_format($prices['k16']) }})</option>
                      <option value="{{ $prices['k14'] }}">k14 ({{ number_format($prices['k14']) }})</option>
                      <option value="{{ $prices['k12'] }}">k12 ({{ number_format($prices['k12']) }})</option>
                      <option value="{{ $prices['k10'] }}">k10 ({{ number_format($prices['k10']) }})</option>
                      <option value="{{ $prices['k9'] }}">k9 ({{ number_format($prices['k9']) }})</option>
                      <option value="{{ $prices['pttokutei'] }}">PT特定 ({{ number_format($prices['pttokutei']) }})</option>
                      <option value="{{ $prices['pt1000'] }}">PT1000 ({{ number_format($prices['pt1000']) }})</option>
                      <option value="{{ $prices['pt950'] }}">PT950 ({{ number_format($prices['pt950']) }})</option>
                      <option value="{{ $prices['pt900'] }}">PT900 ({{ number_format($prices['pt900']) }})</option>
                      <option value="{{ $prices['pt850'] }}">PT850 ({{ number_format($prices['pt850']) }})</option>
                      <option value="{{ $prices['pt800'] }}">PT800 ({{ number_format($prices['pt800']) }})</option>
                      <option value="{{ $prices['pt750'] }}">PT750 ({{ number_format($prices['pt750']) }})</option>
                      <option value="{{ $prices['silver'] }}">silver ({{ number_format($prices['silver']) }})</option>
                    </select>
                    
                  </div>
                </div>
                <h3 class="font-semibold mt-3 mb-1 text-base">コンビの場合の比率</h3>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                  <div class="flex items-center gap-2">
                  <h4 class="font-medium whitespace-nowrap">①:</h4>
                  <input type="number" v-model="row.ratio1" :name="'ratio1[' + index + ']'" class="w-20 border rounded px-2 py-1" :max="100" :min="0" step="1" /> %
                 
                  </div>
                  <div class="flex items-center gap-2">
                  <h4 class="font-medium whitespace-nowrap">②:</h4>
                  <span class="inline-block w-20 border rounded px-2 py-1 bg-gray-100 text-center">@{{ 100 - row.ratio1 }}</span> %
                  </div>
                </div>
                <div class="flex items-center gap-2 mt-3 mb-1">
                  <h3 class="font-semibold text-base whitespace-nowrap">買取率:</h3>
                  <input type="number" v-model="row.calc" :name="'calc[' + index + ']'" class="w-20 border rounded px-2 py-1" /> %
                 
                </div>
                <h4 class="font-semibold mt-3 mb-1">1g当たりの買取金額</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                  <div class="flex items-center gap-2">
                  <h5 class="font-medium whitespace-nowrap">①:</h5>
                  <p class="font-mono text-blue-700">@{{ price_per1(row) }}</p>
                  </div>
                  <div class="flex items-center gap-2">
                  <h5 class="font-medium whitespace-nowrap">②:</h5>
                  <p class="font-mono text-blue-700">@{{ price_per2(row) }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-2 mt-3 mb-1">
                  <h3 class="font-semibold text-base whitespace-nowrap">重さ:</h3>
                  <input type="number" v-model="row.weight" :name="'weight[' + index + ']'" class="w-24 border rounded px-2 py-1" :min="0" /> <span class="ml-1">グラム</span>
                </div>
                <h3 class="font-semibold mt-3 mb-1 text-base">計算結果</h3>
                <p class="text-green-700">①: @{{ formatPrice(calculatePart1Total(row)) }}</p>
                <p class="text-green-700">②: @{{ formatPrice(calculatePart2Total(row)) }}</p>
                <p class="font-bold text-lg text-green-800">合計: @{{ formatPrice(calculateRowTotal(row)) }}</p>
                <p class="text-xs">※四捨五入して算出しています</p>

                <div class="flex">
                  <div class="mt-4 mx-auto">
                    <a @click="confirm(index)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">確定</a>
                  </div>
                  
                  <div class="mt-4 mx-auto">
                    <a @click="gold_calc_show(index)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">閉じる</a>
                  </div>
                </div>


                </div>
              </div>
              </td>
              <td class="border px-2 py-1 text-center">
              <a @click="removeRow(index)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">削除</a>
              </td>
            </tr>
             <tr>
            <td class="border px-2 py-2"></td>
            <td class="border px-2 py-2"></td>
            <td class="border px-2 py-2">調整金額:</td>
            <td><p><input type="number" v-model.number="adjustment" name="adjustment" class="w-30 border rounded px-2 py-1"></p></td>
            <td class="border px-2 py-2"></td>
             </tr>
               <tr>
            <td class="border px-2 py-2"></td>
            <td class="border px-2 py-2"></td>
            <td class="border px-2 py-2"><p class="ml-auto text-lg font-semibold">全体合計:</p> </td>
            <td><span class="text-blue-700 text-lg font-semibold">@{{ formatPrice(totalSubtotal) }}</span> 円</td>
            <td class="border px-2 py-2"></td>
             </tr>
     
            </tbody>
          </table>
          <div class="flex items-center gap-4">
            <a @click="addRow" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">行を追加</a>
            
             <button class="bg-blue-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">確定</button>
           </form>
          </div>
  </div>


 
  <script>


Vue.createApp({
  data() {
    return {
    Visible: false
    };
  },
  methods: {
    tanaka() {
      this.Visible = !this.Visible;
    }
  }


 }).mount('#tanka');
// モーダルや新規行で使う共通の初期値
const createEmptyRow = () => ({
  text: '',
  remarks: '',
  num1: 0,
  num2: 1,
  gold1: '',
  gold2: '0',
  ratio1: 100,
  calc: 80,
  weight: 0,
  showGoldCalcForRow: false,
});

// PHPから渡された$Estimateオブジェクト（JSON形式）をJS変数に格納
const initialEstimate = @json($Estimate);
const initialItems = initialEstimate.items || [];

// バリデーションエラー時は old() の値を優先
const oldInputs = {
  text: @json(old('text', [])),
  num1: @json(old('num1', [])),
  num2: @json(old('num2', [])),
  remarks: @json(old('remarks', [])),
  gold1: @json(old('goldlist', [])),
  gold2: @json(old('goldlist2', [])),
  ratio1: @json(old('ratio1', [])),
  calc: @json(old('calc', [])),
  weight: @json(old('weight', [])),
};

const hasOldRows = Array.isArray(oldInputs.text) && oldInputs.text.length > 0;

const rowsFromOld = hasOldRows
  ? oldInputs.text.map((text, index) => ({
      ...createEmptyRow(),
      text: text || '',
      num1: Number(oldInputs.num1[index] ?? 0) || 0,
      num2: Number(oldInputs.num2[index] ?? 1) || 1,
      remarks: oldInputs.remarks[index] || '',
      gold1: oldInputs.gold1[index] ?? '',
      gold2: oldInputs.gold2[index] ?? '0',
      ratio1: Number(oldInputs.ratio1[index] ?? 100) || 100,
      calc: Number(oldInputs.calc[index] ?? 80) || 80,
      weight: Number(oldInputs.weight[index] ?? 0) || 0,
    }))
  : [];

const rowsFromEstimate = initialItems.map(item => ({
  ...createEmptyRow(),
  text: item.text || '',
  num1: Number(item.num1 ?? 0) || 0,
  num2: Number(item.num2 ?? 1) || 1,
  remarks: item.remarks || '',
}));

const initialRows = rowsFromOld.length
  ? rowsFromOld
  : (rowsFromEstimate.length ? rowsFromEstimate : [createEmptyRow()]);

const initialAdjustment = Number(@json(old('adjustment', $Estimate->adjustment ?? 0))) || 0;
const initialTitle = @json(old('title', $Estimate->title ?? ''));


 Vue.createApp({
      data() {
        return {
          Visible: false,
           // ★修正箇所★：DBから取得したデータで初期化する
          title: initialTitle, 
          adjustment: initialAdjustment,
          rows: initialRows,
                goldPriceLabels: {
            '{{ $prices["k24tokutei"] }}': 'k24特定',
            '{{ $prices["k24"] }}': 'k24',
            '{{ $prices["k22"] }}': 'k22',
            '{{ $prices["k20"] }}': 'k20',
            '{{ $prices["k18"] }}': 'k18',
            '{{ $prices["k16"] }}': 'k16',
            '{{ $prices["k14"] }}': 'k14',
            '{{ $prices["k12"] }}': 'k12',
            '{{ $prices["k10"] }}': 'k10',
            '{{ $prices["k9"] }}': 'k9',
            '{{ $prices["pttokutei"] }}': 'PT特定',
            '{{ $prices["pt1000"] }}': 'PT1000',
            '{{ $prices["pt950"] }}': 'PT950',
            '{{ $prices["pt900"] }}': 'PT900',
            '{{ $prices["pt850"] }}': 'PT850',
            '{{ $prices["pt800"] }}': 'PT800',
            '{{ $prices["pt750"] }}': 'PT750',
            '{{ $prices["silver"] }}': 'silver',
            '0': 'コンビ無し' // gold2 が "0" の場合のラベル
          }
        };
      },
      methods: {
           gold_calc_show(index) {
    this.rows[index].showGoldCalcForRow = !this.rows[index].showGoldCalcForRow;
        },
        addRow() {
  this.rows.push(createEmptyRow());
        },
        removeRow(index) {
      this.rows.splice(index, 1); // ← これで1行削除！
        },


    calculatePart1Total(item) {
  if (item.gold1 && item.calc && item.weight && item.ratio1) {
    return item.gold1 * item.calc / 100 * item.weight * item.ratio1 / 100;
  }
  return 0;
      },
        rowcalc(n){
          return n.num1 * n.num2; //小計出す
        },
        price_per1(currentRow){//1gあたりの買取金額1
          if (currentRow.gold1 && currentRow.calc) {
           return (currentRow.gold1 * currentRow.calc / 100).toLocaleString();
            } else {
            return 0;       
        }},
        price_per2(currentRow){//1gあたりの買取金額2
          if (currentRow.gold2 && currentRow.calc) {
           return (currentRow.gold2 * currentRow.calc / 100).toLocaleString();
            } else {
            return 0;       
        }},
        calculatePart2Total(row) {
          if (row.gold2 && row.calc && row.weight && row.ratio1) {
            return row.gold2 * row.calc / 100 * row.weight * (100 - row.ratio1) / 100;
          }
          return 0;
        },
        calculateRowTotal(row) {
          const part1 = this.calculatePart1Total(row);
          const part2 = this.calculatePart2Total(row);
          return Math.round(part1 + part2);
        },
        formatPrice(value) {
          if (typeof value !== 'number') {
            return 0;
          }
          return value.toLocaleString();
        }
      ,
      confirm(index){

        const currentRow = this.rows[index];

        if(currentRow.remarks){
          currentRow.remarks ="";
        }
        const goldweight = currentRow.weight || 0;
        const gold1Label = this.goldPriceLabels[currentRow.gold1] || (currentRow.gold1 ? `不明(${currentRow.gold1})` : '');
        // gold2が "0" (コンビ無し) の場合はラベルを空にするか、特定の処理をしないようにする
        const gold2Label = (currentRow.gold2 && currentRow.gold2 !== "0") ? (this.goldPriceLabels[currentRow.gold2] || `不明(${currentRow.gold2})`) : '';

        const pricePer1Text = this.price_per1(currentRow); // 既に整形済みか0
        const pricePer2Text = (currentRow.gold2 && currentRow.gold2 !== "0") ? this.price_per2(currentRow) : ''; // gold2が選択されている場合のみ

        const calculatedRowTotalValue = this.calculateRowTotal(currentRow);

        let detailsToAppend = "";

        if (gold1Label) {
          detailsToAppend += `${gold1Label}\n`;
        }
               if (gold2Label) { // gold2Labelが空でない（つまりコンビ有り）の場合のみ追加
          detailsToAppend += `品位：${gold2Label}\n`;
        }
        
        if (currentRow.gold1) { // gold1が選択されていれば価格情報を追加
            detailsToAppend += `①1g当たりの金額: ${pricePer1Text}\n`;
        }
        if (pricePer2Text) { // pricePer2Textが空でない（つまりコンビ有りで価格計算可能）の場合のみ追加
            detailsToAppend += `②1g当たりの金額: ${pricePer2Text}\n`;
        }
        if(goldweight){
          detailsToAppend += `重さ: ${goldweight}g\n`;
        }
        
        currentRow.remarks = currentRow.remarks ? currentRow.remarks + "\n---\n" + detailsToAppend : detailsToAppend;
        currentRow.num1 = calculatedRowTotalValue;
        // モーダルを閉じる
        this.gold_calc_show(index);
        }},
      computed: {
      totalSubtotal() {
              let sum = 0;
        for (const row of this.rows) {
            sum += (row.num1 || 0) * (row.num2 || 0);
            } 
            sum += this.adjustment; // 調整金額を加算
            return sum;
            }
          }
   }).mount('#app');

  </script>





@endsection
