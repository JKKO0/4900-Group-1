const milestonejsContainer = document.querySelector(".milestonejs__container");

let milestoneArray = [
 {
  "milestone_name":"Car Payment (A)",
  "payment_date":"April 2nd, 2024",
  "milestone_action":"GET UNSTUCK",
 },
 {
  "milestone_name":"Mortgage Payment",
  "payment_date":"April 3nd, 2024",
  "milestone_action":"GET UNSTUCK",
 },
 {
  "milestone_name":"Mortgage Payment",
  "payment_date":"April 4nd, 2024",
  "milestone_action":"GET UNSTUCK",
 },

];

function updateMilestone() {
    milestoneArray.forEach((milestone) => {
      
  const milestonejsInner = document.createElement("div");
  milestonejsInner.classList.add("milestonejs__inner");
  milestonejsInner.setAttribute("id", "milestonejs__inner");

  const milestonejsItem = document.createElement("div");
  milestonejsItem.classList.add("milestonejs__item");
  milestonejsItem.setAttribute("id", "milestonejs__item");

  const milestonejsItemIcon = document.createElement("i");
  milestonejsItemIcon.classList.add("fas", "fa-check");
  milestonejsItemIcon.setAttribute("id", "milestonejs__item-icon");

  const milestonejsItemText = document.createElement("div");
  milestonejsItemText.classList.add("milestonejs__item-text");
  milestonejsItemText.setAttribute("id", "milestonejs__item-text");
  milestonejsItemText.textContent = `${milestone.milestone_name}`;

  const milestonejsPayment = document.createElement("div");
  milestonejsPayment.classList.add("milestonejs__payment");
  milestonejsPayment.setAttribute("id", "milestonejs__payment");
  milestonejsPayment.textContent = "Payment made on ";

  const milestonejsPaymentTime = document.createElement("span");
  milestonejsPaymentTime.classList.add("milestonejs__payment-time");
  milestonejsPaymentTime.setAttribute("id", "milestonejs__payment-time");
  milestonejsPaymentTime.textContent = `${milestone.payment_date}`;

  const milestonejsAction = document.createElement("div");
  milestonejsAction.classList.add("milestonejs__action");
  milestonejsAction.setAttribute("id", "milestonejs__action");
  milestonejsAction.textContent = `${milestone.milestone_action}`;

  const milestonejsDetails = document.createElement("i");
  milestonejsDetails.classList.add("fa", "fa-ellipsis-h");
  milestonejsDetails.setAttribute("id", "milestonejs__details");

  milestonejsContainer.append(milestonejsInner);
  milestonejsInner.append(
    milestonejsItem,
    milestonejsPayment,
    milestonejsAction,
    milestonejsDetails
  );

  milestonejsItem.append(milestonejsItemIcon, milestonejsItemText);
  milestonejsPayment.appendChild(milestonejsPaymentTime);
    });
}

// async function getMilestone() {
//   try {
//     // const response = await fetch(apiUrl);
//     // milestoneArray = await response.json();
//     updateMilestone();
//   } catch {
//     // error
//   }
// }

// getMilestone();

async function getMilestone() {
  try {
    // const response = await fetch(apiUrl);
    // milestoneArray = await response.json();
    updateMilestone();
  } catch {
    // error
  }
}

getMilestone();
