"use strict";

// Retreive the data from the url
const params = new URLSearchParams(window.location.search);
const productName = document.getElementById("productName");
const productImg = document.getElementById("productImg");
const deleteProductCategory = document.getElementById("deleteProductCategory");
const deleteProductStatus = document.getElementById("deleteProductStatus");

productName.textContent = params.get("name");
productImg.src = params.get("image");
deleteProductCategory.textContent = params.get("category");
deleteProductStatus.textContent = params.get("stock");
