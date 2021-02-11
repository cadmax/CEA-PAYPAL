<style>
    input[type=text], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        width: 100%;
        background-color: cornflowerblue;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type=submit]:hover {
        background-color: blue;
    }

    .content {
        position: fixed;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    .title {
        color: black;
        font-family:sans-serif;
        margin: 0 0 24px;
        text-align: center;
        text-transform: uppercase;
        margin-bottom: 500px;
    }

    .price-text {
        color: black;
        font-family:
                'Raleway',sans-serif;
        font-weight: 800;
        line-height: 72px;
        margin: 0 0 24px;
        text-align: center;
        text-transform: uppercase;
    }

    form {
        text-align: center;
    }

    .label-success {
        background-color: #22c6ab;
        text-align: center;
        font-family:
                'Raleway',sans-serif;
        font-weight: 800;
        line-height: 72px;
        margin: 0 0 24px;
        text-align: center;
    }

    .label-failed {
        background-color: #FF6A6A;
        text-align: center;
        font-family:
                'Raleway',sans-serif;
        font-weight: 800;
        line-height: 72px;
        margin: 0 0 24px;
        text-align: center;
    }
</style>


<div class="content">
    <div style="text-align: center">
        <img src="img/paypal-logo.png" alt="logo" width="200px">
    </div>
    <form method="POST" id="payment-form"  action="{{ route('paypal_payment') }}">
        {{ csrf_field() }}

        <label for="amount" class="price-text">Price</label>

        <select class="form-control" id="item_1" name="item_1" required>
            <option value="">Selecione o plano</option>
            <option value="1000">Assinatura 1 Mẽs: R$10,00</option>
            <option value="2000">Assinatura 3 Mêses: R$20,00</option>
            <option value="8000">Assinatura 12 Mêses: R$80,00</option>
        </select>

        <input type="submit" value="Submit">
    </form>

    <?php use Illuminate\Support\Facades\Session; $success = Session::get('success'); $error = Session::get('error'); ?>

    @if ($success = Session::get('success'))
        <div class="w3-panel w3-green w3-display-container">

          <span onclick="this.parentElement.style.display='none'"

                class="w3-button w3-green w3-large w3-display-topright">&times;</span>

            <p class="label-success">{!! $success !!}</p>

        </div>

        <?php Session::forget('success');?>
    @endif


    @if ($error = Session::get('error'))
        <div class="w3-panel w3-red w3-display-container">

            <span onclick="this.parentElement.style.display='none'" class="w3-button w3-red w3-large w3-display-topright">&times;</span>

            <p class="label-failed">{!! $error !!}</p>

        </div>


        <?php Session::forget('error');?>
    @endif
</div>
