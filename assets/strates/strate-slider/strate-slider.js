import Slider from "../../components/slider/slider.js";
import Breakpoint from "../../modules/breakpoint";

export default el => {
    const slider = el.querySelector(".slider");
    const myslider = new Slider(slider);
   
    const breakpoint = new Breakpoint(800);
    breakpoint.above = () => { myslider.add(); };
    breakpoint.under = () => { myslider.remove(); };

}