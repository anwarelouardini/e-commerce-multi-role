"use strict";

import {
  initCategoryManager,
  initImageUpload,
} from "../../components/product-form.js";

const categoryContainer = document.querySelector(".btn-container--2");
const newBtnCategory = document.querySelector(".btn-add");
const uploadPrompt = document.querySelector(".upload-prompt");
const mainImageEl = document.querySelector(".main-product-img");
const mainImageInput = document.getElementById("mainImage");
const deleteImgBtn = document.querySelector(".media-img__delete");
const mediaImgContainer = document.querySelector(".media-img-container");

initCategoryManager(categoryContainer, newBtnCategory);
initImageUpload(
  uploadPrompt,
  mainImageInput,
  mainImageEl,
  deleteImgBtn,
  mediaImgContainer,
);
