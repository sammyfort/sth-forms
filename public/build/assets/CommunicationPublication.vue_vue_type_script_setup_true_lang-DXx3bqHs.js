import{_ as o}from"./InputSelect.vue_vue_type_script_setup_true_lang-Cf5gD1vr.js";import{d as a,c as m,o as d,b as r,a as t}from"./app-CqEa0Bzm.js";const l={class:"bg-white rounded-xl shadow-sm border p-6"},c={class:"grid grid-cols-2 gap-6"},b=a({__name:"CommunicationPublication",props:{form:{},yesno:{}},setup(i){const e=i,s=[{label:"I agree",value:!0}];return(u,n)=>(d(),m("div",l,[n[0]||(n[0]=r("h2",{class:"text-lg font-semibold mb-6"},"Step 7:Communication and Publication",-1)),r("div",c,[t(o,{form:e.form,label:`Would you want the STH Research and Development unit to organize a scientific forum
                         for you to present your research protocol to STH scientific community?`,model:"organize_forum",options:e.yesno,required:""},null,8,["form","options"]),t(o,{form:e.form,label:`Would you want your research
                         finding(s) to be posted on the STH website?`,model:"post_on_sth_website",options:e.yesno,required:""},null,8,["form","options"]),t(o,{form:e.form,label:`I hereby confirm that the information
                          provided is true, complete, and accurate to the
                          best of my knowledge. I understand that providing
                          false or misleading information to obtain R&D
                          approval or to conduct studies within STH is strictly
                          prohibited. STH reserves the right to suspend or
                          terminate any study if it is later discovered that
                          falsified information was submitted. Please review t
                          he statement above and click the
                          'I Agree' button below to indicate your agreement.`,model:"agreement",options:s,required:""},null,8,["form"])])]))}});export{b as _};
