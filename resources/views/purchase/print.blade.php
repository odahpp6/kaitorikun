@extends('layouts.member')

@section('title', '買取計算書（売買契約書）')
@section('content')
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

<style>
    @media print {
        .print-hidden {
            display: none !important;
        }
        .print-area {
            margin: 0;
            padding: 0;
        }
        body {
            background: #ffffff;
        }
        .print-table th,
        .print-table td {
            border-color: #000000 !important;
        }
    }

    
</style>

<div class="max-w-6xl mx-auto p-6 space-y-6 print-area">
    <div class="flex items-center justify-between border-b-2 border-gray-800 pb-2">
        <h2 class="text-2xl font-bold text-gray-800">買取計算書（売買契約書）</h2>
        <button type="button" class="print-hidden px-4 py-2 text-sm font-semibold bg-gray-800 text-white rounded" onclick="window.print()">
            印刷する
        </button>
    </div>

    <table class="w-full border border-gray-800 text-sm print-table">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-800 px-3 py-2 text-left" colspan="4">購入事業者情報</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="border border-gray-800 px-3 py-2 w-1/6">購入事業者</th>
                <td class="border border-gray-800 px-3 py-2" colspan="3">{{ $store->company_name ?? '—' }}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">住所</th>
                <td class="border border-gray-800 px-3 py-2" colspan="3">
                    {{ $store?->postal_code ? '〒' . $store->postal_code . ' ' : '' }}{{ $store->address ?? '—' }}
                </td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">電話番号</th>
                <td class="border border-gray-800 px-3 py-2" colspan="3">{{ $store->phone_number ?? '—' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="w-full border border-gray-800 text-sm print-table">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-800 px-3 py-2 text-left" colspan="4">顧客情報</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="border border-gray-800 px-3 py-2 w-1/6">顧客名</th>
                <td class="border border-gray-800 px-3 py-2 w-2/6">{{ $customer->name ?? '—' }}</td>
                <th class="border border-gray-800 px-3 py-2 w-1/6">フリガナ</th>
                <td class="border border-gray-800 px-3 py-2 w-2/6">{{ $customer->furigana ?? '—' }}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">身分証明書種類</th>
                <td class="border border-gray-800 px-3 py-2">{{ $customer->proof_type ?? '—' }}</td>
                <th class="border border-gray-800 px-3 py-2">本人確認番号</th>
                <td class="border border-gray-800 px-3 py-2">{{ $customer->proof_num ?? '—' }}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">電話番号</th>
                <td class="border border-gray-800 px-3 py-2">{{ $customer->phone_number ?? '—' }}</td>
                <th class="border border-gray-800 px-3 py-2">Email</th>
                <td class="border border-gray-800 px-3 py-2">{{ $customer->email ?? '—' }}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">生年月日</th>
                <td class="border border-gray-800 px-3 py-2">{{ $birthDate ?? '—' }}</td>
                <th class="border border-gray-800 px-3 py-2">性別</th>
                <td class="border border-gray-800 px-3 py-2">{{ $gender ?? '—' }}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">職業</th>
                <td class="border border-gray-800 px-3 py-2">{{ $customer->occupation ?? '—' }}</td>
                <th class="border border-gray-800 px-3 py-2">郵便番号</th>
                <td class="border border-gray-800 px-3 py-2">{{ $customer->postal_code ?? '—' }}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">都道府県</th>
                <td class="border border-gray-800 px-3 py-2">{{ $customer->prefecture ?? '—' }}</td>
                <th class="border border-gray-800 px-3 py-2">市区町村</th>
                <td class="border border-gray-800 px-3 py-2">{{ $customer->city ?? '—' }}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">番地以降</th>
                <td class="border border-gray-800 px-3 py-2">{{ $customer->address_detail ?? '—' }}</td>
                <th class="border border-gray-800 px-3 py-2">建物名</th>
                <td class="border border-gray-800 px-3 py-2">{{ $customer->address_building ?? '—' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="w-full border border-gray-800 text-sm print-table">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-800 px-3 py-2 text-left" colspan="4">取引情報</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="border border-gray-800 px-3 py-2 w-1/6">登録日時</th>
                <td class="border border-gray-800 px-3 py-2 w-2/6">{{ $deal->created_at?->format('Y/m/d H:i') ?? '—' }}</td>
                <th class="border border-gray-800 px-3 py-2 w-1/6">伝票番号</th>
                <td class="border border-gray-800 px-3 py-2 w-2/6">{{ $deal->slip_number ?? '—' }}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">お支払い方法</th>
                <td class="border border-gray-800 px-3 py-2">{{ $paymentMethod }}</td>
                <th class="border border-gray-800 px-3 py-2">買取区分</th>
                <td class="border border-gray-800 px-3 py-2">{{ $deal->buy_type ?? '—' }}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">備考</th>
                <td class="border border-gray-800 px-3 py-2 whitespace-pre-wrap" colspan="3">{!! nl2br(e($paymentRemarks)) !!}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">来店区分</th>
                <td class="border border-gray-800 px-3 py-2">{{ $deal->arrival_type ?? '—' }}</td>
                <th class="border border-gray-800 px-3 py-2">適格請求書発行事業者</th>
                <td class="border border-gray-800 px-3 py-2">{{ $deal->invoice_issuer ?? '—' }}</td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">取引備考</th>
                <td class="border border-gray-800 px-3 py-2 whitespace-pre-wrap" colspan="3">{!! nl2br(e($dealRemarks)) !!}</td>
            </tr>
        </tbody>
    </table>

    <table class="w-full border border-gray-800 text-sm print-table">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-800 px-2 py-2">#</th>
                <th class="border border-gray-800 px-2 py-2">商品名</th>
                <th class="border border-gray-800 px-2 py-2">買取分類</th>
                <th class="border border-gray-800 px-2 py-2">備考</th>
                <th class="border border-gray-800 px-2 py-2">個数</th>
                <th class="border border-gray-800 px-2 py-2">買取金額</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($deal->buyItems as $index => $item)
                <tr>
                    <td class="border border-gray-800 px-2 py-1 text-center">{{ $index + 1 }}</td>
                    <td class="border border-gray-800 px-2 py-1">{{ $item->product }}</td>
                    <td class="border border-gray-800 px-2 py-1">{{ $item->classification }}</td>
                    <td class="border border-gray-800 px-2 py-1 whitespace-pre-wrap">{!! nl2br(e($item->remarks_2)) !!}</td>
                    <td class="border border-gray-800 px-2 py-1 text-right">{{ $item->quantity ?? 1 }}</td>
                    <td class="border border-gray-800 px-2 py-1 text-right">{{ number_format($item->buy_price) }}</td>
                </tr>
            @empty
                <tr>
                    <td class="border border-gray-800 px-2 py-4 text-center text-gray-500" colspan="6">商品が登録されていません</td>
                </tr>
            @endforelse
            <tr class="bg-gray-100">
                <th class="border border-gray-800 px-2 py-2 text-right" colspan="5">合計金額</th>
                <td class="border border-gray-800 px-2 py-2 text-right">{{ $deal->total_price !== null ? number_format($deal->total_price) : '—' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="w-full border border-gray-800 text-sm print-table">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-800 px-3 py-2 text-left" colspan="4">同意・署名</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="border border-gray-800 px-3 py-2 w-1/6">同意事項</th>
                <td class="border border-gray-800 px-3 py-2" colspan="3">
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>提示金額を受領しました。</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>買取商品の返品は致しかねます。また、お売り頂いた商品が盗品・コピー品と判明した場合はご返金頂きます。</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>個人情報取り扱いに関する表明の説明を受け、個人情報の取扱いについて同意します。</span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">同意事項詳細</th>
                <td class="border border-gray-800 px-3 py-2 text-xs" colspan="3">
                <ul class="list-disc list-inside space-y-2 text-xs">
                    <li>買取商品が、盗難品・コピー商品（贋物）でないことを前提として買い取りをさせていただいております。万が一、 買取商品が盗難品又はコピー商品（贋物）であることが後に判明した場合には、当社は本取引を取り消し又は解除して代金全額をご返金いただきます。</li>
                    <li>この度買い取りさせていただきました商品は、お客様都合による返品には応じられませんので了承ください。</li><li>数量違い、単価違い等についても応じられませんので、よくご確認ください。</li><li>数量違い、単価違い等についても応じられませんので、よくご確認ください。</li>
                    <li>記載の個人情報取り扱いに関する表明を理解し、当店との古物売買取引に関して当店が収集する個人情報（買取、査定、問合せ、資料請求の申し込み、アンケート調査への回答、キャンペーン等の応募の申し込み等により収集する場合を含みます）については、裏面に記載する事項の説明を受け、記載の取り扱いについて同意します。</li>
                </ul>
                       </td>
            </tr>
            <tr>
                <th class="border border-gray-800 px-3 py-2">署名</th>
                <td class="border border-gray-800 px-3 py-2" colspan="3">
                    @if (!empty($deal->signature_image_data))
                        <img src="{{ asset('storage/' . $deal->signature_image_data) }}" alt="署名" class="mt-2 w-full max-w-xl h-40 object-contain border border-gray-200 rounded">
                    @else
                        <span class="text-gray-500">未登録</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <table class="w-full border border-gray-800 text-sm print-table">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-800 px-3 py-2 text-left" colspan="4">個人情報取り扱いに関する表明</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border border-gray-800 px-3 py-3 text-xs leading-relaxed whitespace-pre-wrap" colspan="4">
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
                <th class="border border-gray-800 px-3 py-2 w-1/6">情報の管理責任者</th>
                <td class="border border-gray-800 px-3 py-2" colspan="3">
                    {{ $store->company_name ?? '—' }}<br>
                    {{ $store?->postal_code ? '〒' . $store->postal_code . ' ' : '' }}{{ $store->address ?? '—' }}　TEL.{{ $store->phone_number ?? '—' }}
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
