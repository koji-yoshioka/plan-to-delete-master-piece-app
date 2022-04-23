export interface ReqLogo {
  logo: File | null
  preview: string | ArrayBuffer | null
  isChanged: boolean
}

export interface ReqImage {
  displayNo: number
  image: File | null
  preview: string | ArrayBuffer | null
  isChanged: boolean
}

export interface ResCompanyProfile {
  data: {
    name: string | null
    email: string | null
    tel: string | null
    zipCode: string | null
    prefecture: string | null
    city: string | null
    restAddress: string | null
    nearestStation: string | null
    businessHoursFrom: string | null
    businessHoursTo: string | null
    comment: string | null
    note: string | null
    logo: string | ArrayBuffer | null
    images: {
      displayNo: number
      fileName: string | ArrayBuffer | null
    }[]
    sellingPoints: {
      id: number
      name: string
      selected: boolean
    }[]
    paymentMethods: {
      id: number
      name: string
      selected: boolean
    }[]
    holidays: {
      id: number
      name: string
      selected: boolean
    }[]
  }
}

export interface ResZipAddress {
  data: {
    pref: string
    city: string
    town: string
  }
}

export interface SellingPoint {
  id: number
  name: string
}

export interface PaymentMethod {
  id: number
  name: string
}

export interface Holiday {
  id: number
  name: string
}
