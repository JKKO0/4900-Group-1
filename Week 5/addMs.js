   function addMilestones(goal_type){
        const milestones_list = {
            10:[
                {
                    goal_milestone_type: 10,
                    goal_milestone_text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do",
                    goal_milestone_title: "Gather required documents",
                    goal_milestone_keyword: "Car"                    
                },
                {
                    goal_milestone_type: 10,
                    goal_milestone_text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do",
                    goal_milestone_title: "Budget plan",
                    goal_milestone_keyword: "Car"   
                },
                {
                    goal_milestone_type: 10,
                    goal_milestone_text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do",
                    goal_milestone_title: "Narrow down desired vehicle",
                    goal_milestone_keyword: "Car"   
                },
                {
                    goal_milestone_type: 10,
                    goal_milestone_text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do",
                    goal_milestone_title: "Down payment/Loan qualifying",
                    goal_milestone_keyword: "Car"   
                }
            ]
        }

        const milestones = milestones_list[goal_type];
        
        $.ajax({
            url: "milestones_save",
            type: "POST",
            dataType: 'application/json',
            data: {milestones},
            success:    (data) => { 
                if(data.success) {
                    botAlert('#link_alert', 6000, data.message);
                    location.reload();
                } else {
                    botAlert('#link_alert', 6000, data.message);
                }
            },
            error:  (xhr, errorThrown) => {
                if(xhr.status === 400){
                    try {
                        let response = JSON.parse(xhr.responseText);
                        botAlert('#link_alert', 6000, response.message);
                    } catch(e){
                        botAlert('#link_alert', 6000, 'An error occurred. Please try again later.');
                    }
                } else {
                    botAlert('#link_alert',6000, 'An error occurred. Please try again later.');
                }
                console.log(errorThrown);
            },
        });
    }
