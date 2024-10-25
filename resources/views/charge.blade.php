<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement sécurisé</title>

    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>

    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center mb-6 text-gray-700">Paiement sécurisé</h2>

        <form action="/charge" method="POST" id="payment-form">
            {{ csrf_field() }}

            <!-- Montant -->
            <label for="amount" class="block text-gray-600 mb-1">Montant (en centimes) :</label>
            <input type="text" name="amount" id="amount" placeholder="Ex : 5000" required
                class="w-full px-4 py-2 border rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <!-- Email -->
            <label for="email" class="block text-gray-600 mb-1">Email :</label>
            <input type="text" name="email" id="email" placeholder="Ex : exemple@email.com" required
                class="w-full px-4 py-2 border rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <!-- Carte de paiement -->
            <label for="card-element" class="block text-gray-600 mb-1">Carte de crédit ou de débit :</label>
            <div id="card-element" class="p-2 border rounded-lg mb-4">
                <!-- Une Stripe Element sera insérée ici -->
            </div>

            <!-- Logos des cartes acceptées -->
            <div class="flex justify-center space-x-4 mb-4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa"
                    class="h-6">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Mastercard_2019_logo.svg" alt="Mastercard"
                    class="h-6">
            </div>

            <!-- Erreurs de formulaire -->
            <div id="card-errors" role="alert" class="text-red-500 text-sm mb-4"></div>

            <!-- Bouton de soumission -->
            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                Payer maintenant
            </button>
        </form>
    </div>

    <script>
        const stripe = Stripe('VOTRE_CLE_PUBLIQUE_STRIPE');
        const elements = stripe.elements();

        const style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#a0aec0"
                }
            },
            invalid: {
                color: "#e63946",
                iconColor: "#e63946"
            }
        };

        const card = elements.create("card", {
            style: style
        });
        card.mount("#card-element");

        card.on("change", function(event) {
            const displayError = document.getElementById("card-errors");
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = "";
            }
        });

        const form = document.getElementById("payment-form");
        form.addEventListener("submit", function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    const errorElement = document.getElementById("card-errors");
                    errorElement.textContent = result.error.message;
                } else {
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            const form = document.getElementById("payment-form");
            const hiddenInput = document.createElement("input");
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", "stripeToken");
            hiddenInput.setAttribute("value", token.id);
            form.appendChild(hiddenInput);

            form.submit();
        }
    </script>
</body>

</html>
