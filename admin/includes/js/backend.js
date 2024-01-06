// Calc The Descount in Add item Form
let price = document.querySelector(".users-container .prices .price")
let disc = document.querySelector(".users-container .prices .discount")
let n_price = document.querySelector(".users-container .prices .new_price")

if (price) {

    price.addEventListener("input", () => {
        n_price.value = +price.value - (+price.value * (+disc.value / 100));
    })

    disc.addEventListener("input", () => {
        n_price.value = +price.value - (+price.value * (+disc.value / 100));
    })
}

// ##################################################
// ##################################################
// ##################################################


// Remove The Box show mss from the page
let shoMssg = document.querySelector(".message-box");

if (shoMssg) {
    setTimeout(() => {
        shoMssg.remove();
    }, 1500)
}

// ##################################################
// ##################################################
// ##################################################

// Change the status point color of item with select box
let selectBox = document.querySelector(".users-container .status select");

let point = document.querySelector(".users-container .status .point");

if (selectBox) {
    selectBox.onchange = function () {
        if (selectBox.value == 1) {
            point.classList.remove("pointRed")
            point.classList.add("pointVert")
        } else {
            point.classList.remove("pointVert")
            point.classList.add("pointRed")
        }
    }
}


// ##################################################
// ##################################################
// ##################################################

let AllBtnEdit = document.querySelectorAll(".grid-container .item4 .EditProduct i");
let BoxEdit = document.querySelector(".grid-container .boxEdit");

if (AllBtnEdit) {
    AllBtnEdit.forEach(btn => {
        btn.addEventListener("click", () => {
            let Quantity_value = btn.nextElementSibling.value;
            let itemID = btn.nextElementSibling.nextElementSibling.value;
            let orderID = btn.nextElementSibling.nextElementSibling.nextElementSibling.value;

            BoxEdit.classList.add("showBox");
            BoxEdit.querySelector(".quantity").value = Quantity_value;
            BoxEdit.querySelector(".itemID").value = itemID;
            BoxEdit.querySelector(".orderID").value = orderID;
        })
    })
}

if (BoxEdit) {
    let cancelBtn = BoxEdit.querySelector(".btns .cancel");

    cancelBtn.addEventListener("click", () => {
        BoxEdit.classList.remove("showBox");
    })
}


// Show And Hide Side Bar.

let sideBar = document.querySelector(".dashboard .sideBar");
let BoxBars = document.querySelector(".dashboard .barsBox");


BoxBars.addEventListener("click", (e) => {
    sideBar.classList.toggle("hide");
    e.target.style.cssText = "transform:scale(0.8)";
    setTimeout(() => {
        e.target.style.cssText = "transform:scale(1)";
    }, 100)
})

window.addEventListener("resize", () => {
    if (window.innerWidth <= 1080) {
        sideBar.classList.add("hide");
    } else {
        sideBar.classList.remove("hide");
    }
})

window.onload = () => {
    if (window.innerWidth <= 1080) {
        sideBar.classList.add("hide");
    }
}