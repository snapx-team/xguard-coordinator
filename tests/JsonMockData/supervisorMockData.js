export function supervisorMockData(isActive = true) {
    return {
        'id': 1,
        'fullName': 'Test User',
        'email': 'test@gmail.com',
        'supervisorShifts': [
            {
                'id': 1,
                'startTime': '2022-04-05T22:00:00.000000Z',
                'endTime': null,
                'isActive': isActive,
                'jobSiteVisits': [
                    {
                        'id': 3,
                        'startTime': null,
                        'endTime': null,
                        'jobSite': {
                            'id': 3,
                            'address': '9494 boulevard st-laurent',
                            'lat': 45.5451245,
                            'lng': -73.6542803,
                            'contracts': [
                                {
                                    'id': 3,
                                    'name': '3 - Test'
                                }
                            ]
                        }
                    },
                    {
                        'id': 4,
                        'startTime': null,
                        'endTime': null,
                        'isActive': isActive,
                        'jobSite': {
                            'id': 16,
                            'address': '13952 de montigny, pointes aux trembles',
                            'lat': 45.6678806,
                            'lng': -73.4996059,
                            'contracts': [
                                {
                                    'id': 16,
                                    'name': '16 - ZZZ-Residence Longpré-ZZZ'
                                }
                            ]
                        }
                    }
                ]
            },
            {
                'id': 2,
                'startTime': '2022-04-01T22:43:40.000000Z',
                'endTime': null,
                'isActive': isActive,
                'jobSiteVisits': [
                    {
                        'id': 2,
                        'startTime': null,
                        'endTime': null,
                        'jobSite': {
                            'id': 2,
                            'address': '3100 Promenade Quartier St-Martin',
                            'lat': 45.5600381,
                            'lng': -73.7486888,
                            'contracts': [
                                {
                                    'id': 2,
                                    'name': '2 - RÉSIDENCE IVVI-Groupe Maurice'
                                }
                            ]
                        }
                    }
                ]
            }
        ]
    };
}
