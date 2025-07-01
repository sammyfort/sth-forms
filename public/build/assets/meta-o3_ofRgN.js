const c=async s=>{const t={metadata:{}};try{const e=await(await fetch(route("api",s))).json();e.success&&(t.metadata=e.data)}catch(a){console.error(a)}return t};export{c as g};
