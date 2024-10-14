document.addEventListener("DOMContentLoaded", function () {
  // Function to add a new row of fields
  function addRow() {
    const container = document.querySelector(".mapping-fields-container");
    const newRow = document
      .querySelector(".mapping-fields-row")
      .cloneNode(true);

    // Reset the new row's select fields
    newRow.querySelector("select[name='ghl_field[]']").value = ""; // Clear GHL field
    // newRow.querySelector("select[name='form_field[]']").value = ""; // Clear Form field
    // Reset the new row's input field for Form field
    const formFieldInput = newRow.querySelector("input[name='form_field[]']");
    formFieldInput.value = ""; // Clear text input for Form field

    // Append the new row to the container
    container.appendChild(newRow);

    // Update options for all rows
    updateFieldOptions();
  }

  // Function to remove a row of fields
  function removeRow(event) {
    const row = event.target.closest(".mapping-fields-row");
    if (document.querySelectorAll(".mapping-fields-row").length > 1) {
      row.remove();
    }
    updateFieldOptions(); // Update the available options after removing a row
  }

  //add and remove row functionality for custom fields.

  function addCustomRow() {
    const container = document.querySelector(
      ".mapping-custom-fields-container"
    );
    const newRow = document
      .querySelector(".custom-mapping-fields-row")
      .cloneNode(true);

    // Reset the new row's select fields
    newRow.querySelector("select[name='ghl_custom_field[]']").value = ""; // Clear GHL field
    //newRow.querySelector("select[name='custom_form_field[]']").value = ""; // Clear Form field
    const formFieldInput = newRow.querySelector(
      "input[name='custom_form_field[]']"
    );
    formFieldInput.value = ""; // Clear text input for Form field
    // Append the new row
    container.appendChild(newRow);

    // Update options for all rows
    // updateFieldOptions();
  }
  // Function to remove a row of fields
  function removeCustomRow(event) {
    const row = event.target.closest(".custom-mapping-fields-row");
    if (document.querySelectorAll(".custom-mapping-fields-row").length > 1) {
      row.remove();
    }
    // updateFieldOptions(); // Update the available options after removing a row
  }

  //add and remove row functionality for custom fields.

  function addOppCustomRow() {
    const container = document.querySelector(
      ".mapping-opp-custom-fields-container"
    );
    const newRow = document
      .querySelector(".opp-custom-mapping-fields-row")
      .cloneNode(true);

    // Reset the new row's select fields
    newRow.querySelector("select[name='ghl_opp_custom_field[]']").value = ""; // Clear GHL field
    //newRow.querySelector("select[name='opp_custom_form_field[]']").value = ""; // Clear Form field
    const formFieldInput = newRow.querySelector(
      "input[name='opp_custom_form_field[]']"
    );
    formFieldInput.value = ""; // Clear text input for Form field
    // Append the new row
    container.appendChild(newRow);

    // Update options for all rows
    // updateFieldOptions();
  }
  // Function to remove a row of fields
  function removeOppCustomRow(event) {
    const row = event.target.closest(".opp-custom-mapping-fields-row");
    if (
      document.querySelectorAll(".opp-custom-mapping-fields-row").length > 1
    ) {
      row.remove();
    }
    // updateFieldOptions(); // Update the available options after removing a row
  }

  // Function to update the GHL field options to exclude already selected ones
  function updateFieldOptions() {
    // Collect all the selected GHL fields from the current rows
    const selectedGhlFields = Array.from(
      document.querySelectorAll("select[name='ghl_field[]']")
    )
      .map((select) => select.value)
      .filter((value) => value !== ""); // Ignore empty values

    // Loop through all GHL dropdowns and update options
    const ghlSelects = document.querySelectorAll("select[name='ghl_field[]']");
    ghlSelects.forEach((select) => {
      const currentValue = select.value; // Preserve the current selected value

      // Clear and rebuild the options for each dropdown
      const options = `
                <option value="">Select GHL Field</option>
                <option value="firstName" ${
                  selectedGhlFields.includes("firstName") &&
                  currentValue !== "firstName"
                    ? "disabled"
                    : ""
                }>First Name</option>
				<option value="lastName" ${
          selectedGhlFields.includes("lastName") && currentValue !== "lastName"
            ? "disabled"
            : ""
        }>Last Name</option>
				<option value="name" ${
          selectedGhlFields.includes("name") && currentValue !== "name"
            ? "disabled"
            : ""
        }>Name</option>
                <option value="email" ${
                  selectedGhlFields.includes("email") &&
                  currentValue !== "email"
                    ? "disabled"
                    : ""
                }>Email</option>
                <option value="phone" ${
                  selectedGhlFields.includes("phone") &&
                  currentValue !== "phone"
                    ? "disabled"
                    : ""
                }>Phone</option>
                <option value="address1" ${
                  selectedGhlFields.includes("address1") &&
                  currentValue !== "address1"
                    ? "disabled"
                    : ""
                }>Address</option>
                <option value="city" ${
                  selectedGhlFields.includes("city") && currentValue !== "city"
                    ? "disabled"
                    : ""
                }>City</option>
                <option value="state" ${
                  selectedGhlFields.includes("state") &&
                  currentValue !== "state"
                    ? "disabled"
                    : ""
                }>State</option>
                <option value="postalCode" ${
                  selectedGhlFields.includes("postalCode") &&
                  currentValue !== "postalCode"
                    ? "disabled"
                    : ""
                }>Postal Code</option>
                <option value="country" ${
                  selectedGhlFields.includes("country") &&
                  currentValue !== "country"
                    ? "disabled"
                    : ""
                }>Country</option>
                <option value="companyName" ${
                  selectedGhlFields.includes("companyName") &&
                  currentValue !== "companyName"
                    ? "disabled"
                    : ""
                }>Business Name</option>
                <option value="website" ${
                  selectedGhlFields.includes("website") &&
                  currentValue !== "website"
                    ? "disabled"
                    : ""
                }>Website</option>
                <option value="timezone" ${
                  selectedGhlFields.includes("timezone") &&
                  currentValue !== "timezone"
                    ? "disabled"
                    : ""
                }>Time Zone</option>
            `;
      select.innerHTML = options;

      // Reapply the previously selected value
      select.value = currentValue;
    });
  }

  // Event listener for the Add Row button
  document
    .querySelector(".mapping-fields-container")
    .addEventListener("click", function (event) {
      if (event.target.classList.contains("add-row")) {
        addRow();
      } else if (event.target.classList.contains("remove-row")) {
        removeRow(event);
      }
    });
  //for custom fields
  document
    .querySelector(".mapping-custom-fields-container")
    .addEventListener("click", function (event) {
      if (event.target.classList.contains("add-custom-row")) {
        addCustomRow();
      } else if (event.target.classList.contains("remove-custom-row")) {
        removeCustomRow(event);
      }
    });
  //for opp custom fields
  document
    .querySelector(".mapping-opp-custom-fields-container")
    .addEventListener("click", function (event) {
      if (event.target.classList.contains("add-opp-custom-row")) {
        addOppCustomRow();
      } else if (event.target.classList.contains("remove-opp-custom-row")) {
        removeOppCustomRow(event);
      }
    });
  // Initialize the first time
  updateFieldOptions();
});

jQuery(document).ready(function ($) {
  var urlParams = new URLSearchParams(window.location.search);

  // Get the 'post' parameter from the URL
  var form_id = urlParams.get("post");
  $.ajax({
    url: ghlcf7pro_form_data.ajaxurl,
    type: "POST",
    data: {
      action: "ghlcf7pro_check_form_data",
      form_id: form_id,
    },
    // beforeSend: function () {
    //   $(".ghl-form").addClass("loading");
    // },
    success: function (response) {
      if (response.data.success === true) {
        var savedPipelineStage = response.data.pipeline_stages; // Get the saved stage from PHP
        var oppcheck = response.data.opp_check;
        if (oppcheck == 'no') {
          $(".ghlcf7pro_opp_hide").css("display", "none");
        }
          // Populate the pipeline stage dropdown based on the selected pipeline
          function populateStages(stages) {
            $("#pipeline-stage")
              .empty()
              .append('<option value="">Select Stage</option>');
            $.each(stages, function (index, stage) {
              $("#pipeline-stage").append(
                $("<option>", {
                  value: stage.id,
                  text: stage.name,
                  selected: stage.id === savedPipelineStage, // Set as selected if it matches the saved value
                })
              );
            });
          }

        // Trigger population of stages based on previously saved pipeline name
        var selectedPipeline = $("#pipeline").val();
        if (selectedPipeline) {
          var stages = $("#pipeline option:selected").data("stages");
          populateStages(stages);
        }

        // Handle pipeline name change
        $("#pipeline").change(function () {
          var stages = $(this).find("option:selected").data("stages");
          populateStages(stages);
        });
      }
    },
  });
});


