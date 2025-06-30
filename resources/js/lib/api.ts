
export const getPromotedSignboards = async ()=>{
    const data = {
        signboards: []
    }
    const request = await fetch(route('signboards.promoted'))
    const response = await request.json()
    if (response.success){
        data.signboards = response.data.signboards
    }
    return data
}
