"use strict";

import {
  initCategoryManager,
  initImageUpload,
} from "../../components/product-form.js";

const params = new URLSearchParams(window.location.search);
const mainImage = document.querySelector(".media-img-container--1 img");
const productTitle = document.getElementById("productTitle");
const productDescription = document.getElementById("productDescription");
const productBasePrice = document.getElementById("basePrice");
const productStockQty = document.getElementById("stockQty");
const confirmEditBtn = document.getElementById("confirmEditBtn");
const categoryContainer = document.querySelector(".btn-container--2");
const newBtnCategory = document.querySelector(".btn-add");
const deleteImgBtn = document.querySelector(".media-img__delete");
const uploadPrompt = document.querySelector(".upload-prompt");
const mainImageEl = document.querySelector(".main-product-img");
const mainImageInput = document.getElementById("mainImage");
const mediaImgContainer = document.querySelector(".media-img-container--1");

initCategoryManager(categoryContainer, newBtnCategory);
initImageUpload(
  uploadPrompt,
  mainImageInput,
  mainImageEl,
  deleteImgBtn,
  mediaImgContainer,
);

const updateData = function () {
  if (!params.get("name")) {
    window.location.href = "./product-overview.html";
    return;
  }
  uploadPrompt.style.display = "none";
  productTitle.value = params.get("name");
  mainImage.src = params.get("image");
  productBasePrice.value = Number(params.get("price"));
  productStockQty.value = Number(params.get("qty"));
  const categoryBtn = document.querySelector(".btn-categorization");
  categoryBtn.textContent = params.get("category");
  categoryBtn.classList.add("btn--active");
};

updateData();
