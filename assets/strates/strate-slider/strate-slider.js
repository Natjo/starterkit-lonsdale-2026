import Slider from "../../components/slider/slider.js";

/* import sheet from "./strate-slider.css" with {type: "css"};
document.adoptedStyleSheets.push(sheet); */


export default el => {
    const slider = el.querySelector(".slider");
    const myslider = new Slider(slider);
    myslider.add();

    const images = el.querySelectorAll("img");

    myslider.onchange = (index, maxindex, percent) => {

        //  images[index].style.translate = `${percent*1}px`;


    }
    



}