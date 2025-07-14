import{t as g}from"./helpers-DybXdqGV.js";import{Z as m}from"./zap-QvPGny9b.js";import{c as i}from"./createLucideIcon-C4J8KU6M.js";import{d as h,c as u,o as x,b as t,a as s,e as b,u as a}from"./app-HbfJ7GXu.js";/**
 * @license lucide-vue-next v0.468.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const y=i("ExternalLinkIcon",[["path",{d:"M15 3h6v6",key:"1q9fwt"}],["path",{d:"M10 14 21 3",key:"gplh6r"}],["path",{d:"M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6",key:"a6xqqp"}]]);/**
 * @license lucide-vue-next v0.468.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const f=i("Share2Icon",[["circle",{cx:"18",cy:"5",r:"3",key:"gq8acd"}],["circle",{cx:"6",cy:"12",r:"3",key:"w7nqdw"}],["circle",{cx:"18",cy:"19",r:"3",key:"1xt0gg"}],["line",{x1:"8.59",x2:"15.42",y1:"13.51",y2:"17.49",key:"47mynk"}],["line",{x1:"15.41",x2:"8.59",y1:"6.51",y2:"10.49",key:"1n3mei"}]]),k={class:"rounded-2xl border border-gray-100 bg-white p-6 shadow-xl"},_={class:"mb-4 flex items-center gap-2 text-xl font-bold text-gray-900"},w={class:"space-y-3"},v=["href"],q={class:"rounded-lg bg-primary p-2 transition-transform duration-200 group-hover:scale-110"},L={class:"rounded-lg bg-primary p-2 transition-transform duration-200 group-hover:scale-110"},C=h({__name:"SignboardActions",props:{signboard:{}},setup(c){const e=c,l=async()=>{const r=route("signboards.show",e.signboard.slug),{gps_lat:o,gps_lon:d}=e.signboard,n=`Check out this signboard on SignboardGh:

Map: ${`https://maps.google.com/?q=${o},${d}`}
Details: ${r}`;if(navigator.share)try{await navigator.share({title:"Signboard Location",text:n,url:r})}catch(p){console.error(p)}else await navigator.clipboard.writeText(n),g("Link copied to clipboard!")};return(r,o)=>(x(),u("div",k,[t("h3",_,[s(a(m),{class:"h-5 w-5 text-primary"}),o[0]||(o[0]=b(" Quick Actions "))]),t("div",w,[t("a",{href:`https://maps.google.com/?q=${e.signboard.gps_lat},${e.signboard.gps_lon}`,target:"_blank",class:"group flex items-center gap-3 rounded-xl bg-orange-50 p-3 text-primary transition-colors duration-200 hover:bg-blue-100"},[t("div",q,[s(a(y),{class:"h-4 w-4 text-white"})]),o[1]||(o[1]=t("span",{class:"font-medium"},"View on Google Maps",-1))],8,v),t("button",{onClick:l,class:"group flex w-full items-center gap-3 rounded-xl bg-orange-50 p-3 text-primary transition-colors duration-200 hover:bg-blue-100"},[t("div",L,[s(a(f),{class:"h-4 w-4 text-white"})]),o[2]||(o[2]=t("span",{class:"font-medium"},"Share Location",-1))])])]))}});export{C as _};
