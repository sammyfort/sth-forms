import { toast } from 'vue-sonner'

export const toastSuccess = (message: string, description?: string, options? : typeof toast) => {
    toast.success(message, {
        description: description,
        ...options
    })
}

export const toastError = (message: string, description?: string, options? : typeof toast) => {
    toast.error(message, {
        description: description,
        ...options
    })
}

export const toastInfo = (message: string, description?: string, options? : typeof toast) => {
    toast.info(message, {
        description: description,
        ...options
    })
}

export const getRandomAuthImage = ()=>{
    const files = [
        "/images/auth/1.png",
        "/images/auth/2.png",
        "/images/auth/3.png",
        "/images/auth/4.png",
        "/images/auth/5.png",
        "/images/auth/6.png",
        "/images/auth/7.png",
        "/images/auth/8.png",
        "/images/auth/9.png",
    ]
    return files[Math.floor(Math.random() * files.length)]
}
