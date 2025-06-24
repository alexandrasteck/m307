/* Dieser JavaScript Code wurde mit KI erstellt und ich habe verstanden was er macht */
window.addEventListener("DOMContentLoaded", () => {
  const selectedPayMethod = document.querySelector(
    'input[name="paymethod"]:checked'
  );
  if (selectedPayMethod) {
    toggleCreditCardFields(selectedPayMethod);
  }
});

function toggleCreditCardFields(el) {
  const ccFields = document.getElementById("creditCard");
  ccFields.style.display = el.value === "kreditkarte" ? "block" : "none";
}

function show_value(val) {
  document.getElementById("amountDisplay").innerText = val;
}
