
/*
  ****
  **** Script To Show And Hide The Menu From The Button Of The Nav Bar
  ****
*/

let userBtn = document.querySelector(".nav-bar .user");
let catBtn = document.querySelector(".nav-bar .list_cats span");

// User Menu
userBtn.addEventListener("click", (e) => {
    userBtn.querySelector(".me_nu").classList.toggle("show");

})

document.addEventListener("click", (e) => {
    if (userBtn.querySelector(".me_nu").classList.contains("show") && e.target.tagName !== "IMG" && e.target.tagName !== "I") {
        userBtn.querySelector(".me_nu").classList.remove("show");
    }
})


// Category menu
catBtn.addEventListener("click", () => {
    catBtn.parentElement.querySelector(".menu-cat").classList.toggle("show");
})


document.addEventListener("click", (e) => {
    if (catBtn.parentElement.querySelector(".menu-cat").classList.contains("show") && e.target.innerHTML !== "الفئات") {
        catBtn.parentElement.querySelector(".menu-cat").classList.remove("show");
    }
})

/*
  ***
  *** Remove Show Message Box From The Page After Time
  ***
*/

let showMssgBox = document.querySelector(".message-box");

if (showMssgBox) {
    setTimeout(() => {
        showMssgBox.remove();
    }, 2000)
}

// Script The Count Of Product
let count = document.querySelector(".ordering .count-pr");
let hiddenCount = document.querySelector(".form_cart .hide");

if (count) {
    count.addEventListener("input", () => {
        hiddenCount.value = count.value;
    })
}


// Auto carossele images in product page
let buttonCaroselle = document.querySelector(".images .carousel .carousel-control-next");

if (buttonCaroselle) {
    setInterval(() => {
        buttonCaroselle.click();
    }, 5000);
}


// Calc All Price in basket products
let AllPricesProduct = document.querySelectorAll(".table-cart .total_price span");

let totalInBasket = document.querySelector(".result .all-price span");

let Total = 0;

if (AllPricesProduct) {
    AllPricesProduct.forEach((price) => {
        Total += +price.innerHTML;
        totalInBasket.innerHTML = Total + " DA";
    })
}


// Button To Top
let BtnTop = document.querySelector(".top");

window.onscroll = function () {
    if (scrollY <= 300) {
        BtnTop.style.cssText = "right: -120px !important; border-radius: 50%; width:5px;height:5px";
    } else {
        BtnTop.style.cssText = "right: 45px !important; border-radius: 0; width:40px;height:40px";
    }
}

BtnTop.addEventListener("click", () => {
    scrollTo({
        top: 0,
        behavior: "smooth"
    })
})

// Show The Form Rating And Box Rating
let btnReview = document.querySelector(".btnReview");
let ReviewBox = document.querySelector(".rating-box")
let ReviewForm = document.querySelector(".rating-box .form-rating");
let cancelBtn = document.querySelector(".rating-box .form-rating .btns .BtnCancel");

if (btnReview) {
    btnReview.addEventListener("click", () => {
        ReviewBox.style.cssText = "opacity:1;pointer-events:all;";
        ReviewForm.style.cssText = "top:80px;";
    })
    cancelBtn.addEventListener("click", () => {
        ReviewBox.style.cssText = "opacity:0;pointer-events:none;";
        ReviewForm.style.cssText = "top:-400px;";
    })
}




// Stars Of Product
let All_groups = document.querySelectorAll(".rating-box .stars .group");

All_groups.forEach((group) => {
    group.addEventListener("click", () => {
        All_groups.forEach(group => {
            group.classList.remove("rated");
        })
        group.classList.add("rated");
    })
})



All_groups.forEach((group) => {
    group.addEventListener("click", () => {
        let AllStars = group.querySelectorAll("i");
        All_groups.forEach((group) => {
            let AllStars = group.querySelectorAll("i");
            AllStars.forEach((star) => {
                star.classList.replace("fa-solid", "fa-regular");
                star.style.cssText = "color:#ffea00";
            })
        })
        AllStars.forEach((star) => {
            star.classList.replace("fa-regular", "fa-solid");
            star.style.cssText = "color:gold";
        })
    })
})

// Add Tracking Code in Local Storage.
let code_track = document.querySelectorAll(".container .my-orders .content table .T_code");

let input_Track = document.querySelector(".container form .input_track");

if (code_track) {
    code_track.forEach(code => {
        code.addEventListener("click", () => {
            localStorage.setItem("codeTrack", code.innerHTML);
        })
    })
}

if (input_Track) {
    input_Track.value = localStorage.getItem("codeTrack");
}

// @media Of Nav Bar Show RightNavBar with Bar Button.
let bars = document.querySelector(".nav-bar-parent .left-nav .bars");
let rightNav = document.querySelector(".nav-bar-parent .right-nav");

bars.addEventListener("click", () => {
    rightNav.classList.toggle("showRightNav");
})

document.addEventListener("click", (e) => {
    if (rightNav.classList.contains("showRightNav") && e.target.tagName !== "I") {
        rightNav.classList.remove("showRightNav");
    }
})