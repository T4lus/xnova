#Python version of vars.php, be careful when editing as python is very particular about tabs and line breaks.
#To update this file simple go tothe admni center and go the the vars.py generation page.
pricelist = {
	202 : { 'metal' : 2000, 'crystal' : 2000, 'deuterium' : 0 },
	203 : { 'metal' : 6000, 'crystal' : 6000, 'deuterium' : 0 },
	204 : { 'metal' : 3000, 'crystal' : 1000, 'deuterium' : 0 },
	205 : { 'metal' : 6000, 'crystal' : 4000, 'deuterium' : 0 },
	206 : { 'metal' : 20000, 'crystal' : 7000, 'deuterium' : 2000 },
	207 : { 'metal' : 45000, 'crystal' : 15000, 'deuterium' : 0 },
	208 : { 'metal' : 10000, 'crystal' : 20000, 'deuterium' : 10000 },
	209 : { 'metal' : 10000, 'crystal' : 6000, 'deuterium' : 2000 },
	210 : { 'metal' : 0, 'crystal' : 1000, 'deuterium' : 0 },
	211 : { 'metal' : 50000, 'crystal' : 25000, 'deuterium' : 15000 },
	212 : { 'metal' : 0, 'crystal' : 2000, 'deuterium' : 500 },
	215 : { 'metal' : 30000, 'crystal' : 40000, 'deuterium' : 15000 },
	213 : { 'metal' : 60000, 'crystal' : 50000, 'deuterium' : 15000 },
	214 : { 'metal' : 5000000, 'crystal' : 4000000, 'deuterium' : 1000000 },

	401 : { 'metal' : 2000, 'crystal' : 0, 'deuterium' : 0 },
	402 : { 'metal' : 1500, 'crystal' : 500, 'deuterium' : 0 },
	403 : { 'metal' : 6000, 'crystal' : 2000, 'deuterium' : 0 },
	404 : { 'metal' : 20000, 'crystal' : 15000, 'deuterium' : 2000 },
	405 : { 'metal' : 2000, 'crystal' : 6000, 'deuterium' : 0 },
	406 : { 'metal' : 50000, 'crystal' : 50000, 'deuterium' : 30000 },
	407 : { 'metal' : 10000, 'crystal' : 10000, 'deuterium' : 0 },
	408 : { 'metal' : 50000, 'crystal' : 50000, 'deuterium' : 0 }
}

CombatCaps = {
	202 : {
		'shield' : 10,
		'attack' : 5,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 1, 402 : 1, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	203 : {
		'shield' : 25,
		'attack' : 5,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 1, 402 : 1, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1
		}
	},
	204 : {
		'shield' : 10,
		'attack' : 50,
		'sd' : {
			202 : 2, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 1, 402 : 1, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	205 : {
		'shield' : 25,
		'attack' : 150,
		'sd' : {
			202 : 3, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 1, 402 : 1, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	206 : {
		'shield' : 50,
		'attack' : 400,
		'sd' : {
			202 : 1, 203 : 1, 204 : 6, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 10, 402 : 1, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	207 : {
		'shield' : 200,
		'attack' : 1000,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 8, 402 : 1, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	208 : {
		'shield' : 100,
		'attack' : 50,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 1, 402 : 1, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	209 : {
		'shield' : 10,
		'attack' : 10,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 1, 402 : 1, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	210 : {
		'shield' : 0,
		'attack' : 0,
		'sd' : {
			202 : 0, 203 : 0, 204 : 0, 205 : 0, 206 : 0, 207 : 0, 208 : 0, 209 : 0, 210 : 0, 211 : 0, 212 : 0, 213 : 0, 214 : 0, 215 : 0, 218 : 0, 401 : 0, 402 : 0, 403 : 0, 404 : 0, 405 : 0, 406 : 0, 407 : 0, 408 : 0, 409 : 1, 410 : 1
		}
	},
	211 : {
		'shield' : 500,
		'attack' : 1000,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 20, 402 : 20, 403 : 10, 404 : 1, 405 : 10, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	212 : {
		'shield' : 100,
		'attack' : 100,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 1, 211 : 1, 212 : 0, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 1, 402 : 1, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	215 : {
		'shield' : 400,
		'attack' : 700,
		'sd' : {
			202 : 3, 203 : 3, 204 : 1, 205 : 4, 206 : 4, 207 : 7, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 1, 218 : 1, 401 : 1, 402 : 1, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	213 : {
		'shield' : 500,
		'attack' : 2000,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 5, 211 : 1, 212 : 5, 213 : 1, 214 : 1, 215 : 2, 218 : 1, 401 : 1, 402 : 10, 403 : 1, 404 : 1, 405 : 1, 406 : 1, 407 : 1, 408 : 1, 409 : 1, 410 : 1
		}
	},
	214 : {
		'shield' : 50000,
		'attack' : 200000,
		'sd' : {
			202 : 250, 203 : 250, 204 : 200, 205 : 100, 206 : 33, 207 : 30, 208 : 250, 209 : 250, 210 : 1250, 211 : 25, 212 : 1250, 213 : 5, 214 : 1, 215 : 15, 218 : 1, 401 : 200, 402 : 200, 403 : 100, 404 : 50, 405 : 100, 406 : 1, 407 : 1, 408 : 1, 409 : 0, 410 : 0
		}
	},

	401 : {
		'shield' : 20,
		'attack' : 80,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 50, 211 : 1, 212 : 0, 213 : 1, 214 : 1, 215 : 1, 218 : 1
		}
	},
	402 : {
		'shield' : 25,
		'attack' : 100,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 50, 211 : 1, 212 : 0, 213 : 1, 214 : 1, 215 : 1, 218 : 1
		}
	},
	403 : {
		'shield' : 100,
		'attack' : 250,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 50, 211 : 1, 212 : 0, 213 : 1, 214 : 1, 215 : 1, 218 : 1
		}
	},
	404 : {
		'shield' : 200,
		'attack' : 1100,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 50, 211 : 1, 212 : 0, 213 : 1, 214 : 1, 215 : 1, 218 : 1
		}
	},
	405 : {
		'shield' : 500,
		'attack' : 150,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 50, 211 : 1, 212 : 0, 213 : 1, 214 : 1, 215 : 1, 218 : 1
		}
	},
	406 : {
		'shield' : 300,
		'attack' : 3000,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 50, 211 : 1, 212 : 0, 213 : 1, 214 : 1, 215 : 1, 218 : 1
		}
	},
	407 : {
		'shield' : 2000,
		'attack' : 1,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 50, 211 : 1, 212 : 0, 213 : 1, 214 : 1, 215 : 1, 218 : 1
		}
	},
	408 : {
		'shield' : 10000,
		'attack' : 1,
		'sd' : {
			202 : 1, 203 : 1, 204 : 1, 205 : 1, 206 : 1, 207 : 1, 208 : 1, 209 : 1, 210 : 50, 211 : 1, 212 : 0, 213 : 1, 214 : 1, 215 : 1, 218 : 1
		}
	}
}