class DateComparer {

	static isExpired(dExpireDate, format = 'Y-m-d')
	{
		let dToday = new Date();
		let expireDate = {};

		if(format === 'd-m-Y') {
			expireDate = new Date();
			const [d, m, Y] = dExpireDate.split('-')
			expireDate.setDate(d)
			expireDate.setMonth(parseInt(m) - 1)
			expireDate.setYear(Y)
			if(dToday.toLocaleDateString() === expireDate.toLocaleDateString()) return false;
			return dToday > expireDate	
		}		

		if(format === 'Y-m-d') {
			expireDate = new Date(dExpireDate);	
			if(dToday.toLocaleDateString() === expireDate.toLocaleDateString()) return false;
			return dToday > expireDate	
		}
	}	
}

// const isExpired = DateComparer.isExpired("1-7-2022", 'd-m-Y');
// console.log(isExpired ? 'Expired' : 'Not expired');