var cart = document.getElementById("cartDropdown");

var changeFunction = function (e) {
    const getItemsLink = document.getElementById("getItems");

    getItemsLink.setAttribute('href', getItemsLink.getAttribute('href').
    replace(/\/(%20|\d+)\//, '/' + cart.value + '/'));
};

cart.addEventListener('change', changeFunction);
cart.dispatchEvent(new Event('change'));
