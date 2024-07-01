<div class="modal-onboarding modal fade animate__animated" id="detailPayment" tabindex="-1" style="display: none;"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="onboarding-content mb-0">
                    <h4 class="onboarding-title text-body"> Detail Payment of {{ $quote->no_quote }}</h4>
                    <div class="onboarding-info mb-3">
                        {{ $quote->pic->client->company }}
                    </div>
                    <form>
                        <div class="row mb-4 text-nowrap">
                            <table class="table table-bordered">
                                @php
                                    $totalAmount = 0;
                                    $remaining = $quote->harga_total;
                                @endphp
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>
                                            <a href="{{ route('download-payment.quotation', $payment->id) }}"
                                                class="btn btn-sm btn-primary d-grid w-100 waves-effect">Pay
                                                Confirmation</a>
                                        </td>
                                        <td>
                                            <p>Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $payment->note }}</p>
                                        </td>
                                    </tr>
                                    @php
                                        $totalAmount += $payment->amount;
                                        $remaining = $quote->harga_total - $totalAmount;
                                    @endphp
                                @endforeach
                            </table>
                            {{-- <div class="col-4">
                                    <a href="#"
                                        class="btn btn-primary d-grid w-100 waves-effect">Download Payment</a>
                                </div>
                                <div class="col-4">
                                </div>
                                <div class="col-4">
                                </div> --}}
                            <hr>
                            <div class="col-6">
                                <h5 class="text-start"> Pay Remaining</h5>
                            </div>
                            <div class="col-6">
                                <h5>: Rp {{ number_format($remaining, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
