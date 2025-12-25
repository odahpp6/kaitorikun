<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'ipaexg';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('ipaexg.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'ipaexg';
            font-style: normal;
            font-weight: bold;
            src: url('{{ public_path('ipaexg.ttf') }}') format('truetype');
        }
        html, body, table, th, td, div, span, p, h1, h2, h3, h4, h5, h6 {
            font-family: 'ipaexg', sans-serif;
        }
        body {
            font-size: 10px;
            line-height: 1.3;
        }
        h1 {
            font-size: 16px;
            text-align: center;
            margin: 0 0 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }
        th {
            background: #f2f2f2;
            text-align: left;
        }
        .section-title {
            background: #e6e6e6;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .small {
            font-size: 9px;
        }
        .terms {
            white-space: pre-wrap;
            line-height: 1.2;
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        .one-line {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .page-break {
            page-break-after: always;
        }
        .signature img,
        .item-img {
            max-height: 120px;
        }
    </style>
</head>
<body>
@php
    $customer = $deal->customer;
    $genderLabels = ['male' => '男性', 'female' => '女性', 'other' => 'その他'];
    $gender = $customer ? ($genderLabels[$customer->gender] ?? $customer->gender) : null;
    $birthDate = null;
    if ($customer && $customer->birth_y && $customer->birth_m && $customer->birth_d) {
        $birthDate = sprintf('%04d/%02d/%02d', $customer->birth_y, $customer->birth_m, $customer->birth_d);
    }
    $paymentMethod = $deal->payment_method ?: '—';
    $paymentRemarks = $deal->payment_remarks ?: '—';
    $dealRemarks = $deal->remarks ?: '—';
@endphp

<h1>買取計算書（売買契約書）</h1>

<table>
    <tr>
        <th class="section-title" colspan="4">購入事業者情報</th>
    </tr>
    <tr>
        <th>購入事業者</th>
        <td colspan="3">{{ $store->company_name ?? '—' }}</td>
    </tr>
    <tr>
        <th>住所</th>
        <td colspan="3">{{ $store?->postal_code ? '〒' . $store->postal_code . ' ' : '' }}{{ $store->address ?? '—' }}</td>
    </tr>
    <tr>
        <th>電話番号</th>
        <td colspan="3">{{ $store->phone_number ?? '—' }}</td>
    </tr>
</table>

<table>
    <tr>
        <th class="section-title" colspan="4">顧客情報</th>
    </tr>
    <tr>
        <th>顧客名</th>
        <td>{{ $customer->name ?? '—' }}</td>
        <th>フリガナ</th>
        <td>{{ $customer->furigana ?? '—' }}</td>
    </tr>
    <tr>
        <th>身分証明書種類</th>
        <td>{{ $customer->proof_type ?? '—' }}</td>
        <th>本人確認番号</th>
        <td>{{ $customer->proof_num ?? '—' }}</td>
    </tr>
    <tr>
        <th>電話番号</th>
        <td>{{ $customer->phone_number ?? '—' }}</td>
        <th>Email</th>
        <td>{{ $customer->email ?? '—' }}</td>
    </tr>
    <tr>
        <th>生年月日</th>
        <td>{{ $birthDate ?? '—' }}</td>
        <th>性別</th>
        <td>{{ $gender ?? '—' }}</td>
    </tr>
    <tr>
        <th>職業</th>
        <td>{{ $customer->occupation ?? '—' }}</td>
        <th>郵便番号</th>
        <td>{{ $customer->postal_code ?? '—' }}</td>
    </tr>
    <tr>
        <th>都道府県</th>
        <td>{{ $customer->prefecture ?? '—' }}</td>
        <th>市区町村</th>
        <td>{{ $customer->city ?? '—' }}</td>
    </tr>
    <tr>
        <th>番地以降</th>
        <td>{{ $customer->address_detail ?? '—' }}</td>
        <th>建物名</th>
        <td>{{ $customer->address_building ?? '—' }}</td>
    </tr>
</table>

<table>
    <tr>
        <th class="section-title" colspan="4">取引情報</th>
    </tr>
    <tr>
        <th>登録日時</th>
        <td>{{ $deal->created_at?->format('Y/m/d H:i') ?? '—' }}</td>
        <th>伝票番号</th>
        <td>{{ $deal->slip_number ?? '—' }}</td>
    </tr>
    <tr>
        <th>お支払い方法</th>
        <td>{{ $paymentMethod }}</td>
        <th>買取区分</th>
        <td>{{ $deal->buy_type ?? '—' }}</td>
    </tr>
    <tr>
        <th>備考</th>
        <td colspan="3" class="one-line">{{ $paymentRemarks }}</td>
    </tr>
    <tr>
        <th>来店区分</th>
        <td>{{ $deal->arrival_type ?? '—' }}</td>
        <th>適格請求書発行事業者</th>
        <td>{{ $deal->invoice_issuer ?? '—' }}</td>
    </tr>
    <tr>
        <th>取引備考</th>
        <td colspan="3" class="one-line">{{ $dealRemarks }}</td>
    </tr>
</table>

<table>
    <tr>
        <th class="section-title" colspan="6">商品情報</th>
    </tr>
    <tr>
        <th class="text-center">#</th>
        <th>商品名</th>
        <th>買取分類</th>
        <th>備考</th>
        <th class="text-right">個数</th>
        <th class="text-right">買取金額</th>
    </tr>
    @forelse ($deal->buyItems as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $item->product }}</td>
            <td>{{ $item->classification }}</td>
            <td>{!! nl2br(e($item->remarks_2)) !!}</td>
            <td class="text-right">{{ $item->quantity ?? 1 }}</td>
            <td class="text-right">{{ number_format($item->buy_price) }}</td>
        </tr>
    @empty
        <tr>
            <td class="text-center" colspan="6">商品が登録されていません</td>
        </tr>
    @endforelse
    <tr>
        <th class="text-right" colspan="5">合計金額</th>
        <td class="text-right">{{ $deal->total_price !== null ? number_format($deal->total_price) : '—' }}</td>
    </tr>
</table>

<table>
    <tr>
        <th class="section-title" colspan="4">同意・署名</th>
    </tr>
    <tr>
        <th>同意事項</th>
        <td colspan="3">
            提示金額受領: {{ $deal->agree_received_amount ? '同意' : '未同意' }} /
            返品不可・盗品時返金: {{ $deal->agree_no_return ? '同意' : '未同意' }} /
            個人情報取扱い同意: {{ $deal->agree_privacy ? '同意' : '未同意' }}
        </td>
    </tr>
    <tr>
        <th>同意事項詳細</th>
        <td colspan="3" class="small terms">
買取商品が、盗難品・コピー商品（贋物）でないことを前提として買い取りをさせていただいております。万が一、
買取商品が盗難品又はコピー商品（贋物）であることが後に判明した場合には、当社は本取引を取り消し又は解除し
て代金全額をご返金いただきます。また、この度買い取りさせていただきました商品は、お客様都合による返品には
応じられませんので了承ください。数量違い、単価違い等についても応じられませんので、よくご確認ください。
裏面記載の個人情報取り扱いに関する表明を理解し、当店との古物売買取引に関して当店が収集する個人情報
（買取、査定、問合せ、資料請求の申し込み、アンケート調査への回答、キャンペーン等の応募の申し込み等により
収集する場合を含みます）については、裏面に記載する事項の説明を受け、記載の取り扱いについて同意します。
        </td>
    </tr>
    <tr class="signature">
        <th>署名</th>
        <td colspan="3">
            @if (!empty($deal->signature_image_data))
                <img src="{{ public_path('storage/' . $deal->signature_image_data) }}" alt="署名">
            @else
                —
            @endif
        </td>
    </tr>
</table>

<div class="page-break"></div>

<table>
    <tr>
        <th class="section-title" colspan="4">個人情報取り扱いに関する表明</th>
    </tr>
    <tr>
        <td colspan="4" class="small terms">
〈個人情報取り扱いに関する表明〉
買取専門店大吉(以下「当店」)は、法令等の定めにより許容されている場合、または取得の状況からみて利用目的が明らかな場合を除き、ご本人さまの同意を得ることなく下記の利用目的の達成を超えて、個人情報を取扱うことはありません。
当店が収集する個人情報とは、個人情報の保護に関する法律第2条にいう個人情報にあたる情報をいいます。
１、個人情報の利用目的ついて
当店がお客様から収集した個人情報は、法令に基づく他、下記目的の必要な範囲で使用いたします。
・買取履歴の確認を行い、充実したサービスを行うため
・買取事業に関連する、新規取扱商品、注力商品、広告の検討資料とするため
・買取事業に関するアフターサービス、新規取扱商品・サービスに関する情報のお知らせを行うため
・郵送買取希望商品査定のお申し込みの確認や査定後返送商品のお届けをするため
・買取及び取引に関するお問い合わせ等に対する回答や確認のご連絡のため
・お客様が買取をご希望する商品等をお伺いするため
・買取・査定サービスを向上させるための分析を行うため
・お客様に合ったサービスを提供するため
・当店及びフランチャイズ本部(株式会社エンパワー)が取り扱う商品、サービスに関する提案、その他情報提供(カタログなどの送付を含みます)
２、第三者ヘの情報の提供等
当店は、上記利用目的達成のため、必要な範囲内で下記の事業者にお客さまの個人情報を提供ないし共同利用し、ー部または全部の管理を委託する場合があります。
なお、お客さまご本人より提供停止のお申し出があった場合は提供等を停止いたします。
事業者　　　　　　　：全国の買取専門店大吉及び株式会社エンパワー(大吉FC本部)
提供等の方法　　　　：書面または口頭による直接提供、または電話による口頭提供、またはFAX、電子メ一ルによる提供等
提供等する情報の項目：上記目的に必要な範囲での、お客様の属性(氏名、住所、年齢、性別、職業等)、来店履歴(来店店舗履歴、過去の査定・買取に関する取引の履歴、電話・メール等による各種お申込みやお問い合わせの履歴、アンケート等に関する事項等)その他当店が買取、査定、問い合わせ、資料請求の申し込み、アンケート調査への回答、キャンペーン等への応募の申し込み等により収集する一切の個人情報
情報の管理責任者：株式会社エンパワー
３、個人情報の管理・保護について
当店が収集したお客様の個人情報については、当店及び提供先においても適切な管理を行い、紛失・破壊・改ざん・不正アクセス・漏えい等の防止に努めます。
４、個人情報の開示・訂正・削除について
個人情報はできるだけ正確かつ最新の状能で管理するよう努めています。
また、個人情報の開示・削除については、お客様ご自身が当社へご連絡ください。
第三者への個人情報の漏えいを防ぐため、当該請求がお客様ご本人によるものであることが確認できた場合に限り対応いたします。
５、お問い合わせ窓口について
当店の個人情報の取扱に関するご意見・ご要望、苦情、その他のお問い合わせにつきましては、当店までお願いいたします。
※出張買取時のクーリングオフのお知らせ
・訪問にてお売りいただいた場合、本書面を受領した日を含む8日間は、書面により無条件に本契約の申し込みの撤回を行うこと（以下「クーリングオフ」といいます。）ができます。
・クーリングオフ期間内は、商品をお客様の手元に残すことができます。
・クーリングオフの効力は、撤回又は解除の書面を発信した時（郵便消印日付）から生じます。
・ハガキ等に必要事項を記入の上ご郵送ください。簡易書留扱いがより確実です。
以上
        </td>
    </tr>
    <tr>
        <th>情報の管理責任者</th>
        <td colspan="3">
            {{ $store->company_name ?? '—' }}<br>
            {{ $store?->postal_code ? '〒' . $store->postal_code . ' ' : '' }}{{ $store->address ?? '—' }}　TEL.{{ $store->phone_number ?? '—' }}
        </td>
    </tr>
</table>

</body>
</html>
