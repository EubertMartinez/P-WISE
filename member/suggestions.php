<?php require_once '../shared/header.php'; ?>
<?php require_once './validation.php'; ?>
<?php
require_once './../data/account/UpdateData.php';
require_once './../repository/account/UpdateRepository.php';

$data = new SuggestionData();
$userData = new UpdateRepository();
$user = $userData->GetUserData($id);

if($_SESSION['parent_parent_id'] != 0) {
    $id = $_SESSION['parent_parent_id'];
}
$CheckTotalNumberOfCyclesWithExpenses = $data->CheckTotalNumberOfCyclesWithExpenses($id);
$suggestion_settings = $data->isCustomizedCyclesOn($id);

?>
<style>
    .toggle-button {

        width: 90px;
        height: 30px;
        border: 3px solid #589C1C;
        border-radius: 25px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        background: #589C1C;
    }

    .slider, #no_of_cycles {
        width: 50%;
        height: 100%;
        border-radius: 25px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: 0.3s;
        position: absolute;
    }


    .toggle-button.on .slider {
        transform: translateX(100%);
        background-color: #fff;
        font-size: 14px;
        font-weight: bold;
    }

    .toggle-button.off .slider {
        transform: translateX(0);
        background-color: #fff;
        font-size: 14px;
        font-weight: bold;
    }
</style>
<div class="container-fluid">
    <h3 class="mt-4 text-site-primary text-center">Suggestions</h3>
    <br>
    <div style="display: flex; gap: .5rem;
        align-items: center;
        text-align: center;">
        <div style="font-size: 14px; font-weight: 600; width: 120px; color: #589C1C;">Customize cycles to be averaged</div>
        <div style="border: 5px; solid black;">
            <div class="toggle-button off" id="toggle">
                <div class="slider" style="BACKGROUND: #D9D9D9;">OFF</div>
            </div>
        </div>
        <div style="color: #589C1C; font-size: 14px; font-weight: 600; width: 200px; justify-content: center; align-items: center;">No. of cycles (with a maximum of 10 recent cycles)</div>
        <div style="border: 5px; solid black;">
            <div class="toggle-button off" id="toggle">
                <select id="no_of_cycles" name="no_of_cycles" style="width: 100%; text-align: center;" >
                    <?php
                    for ($i = 3; $i <= $CheckTotalNumberOfCyclesWithExpenses; $i++) {
                        ?>
                        <option value="<?= $i; ?>"><?= $i; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-12 mb-4">
            <div class="border shadow tile-container h-100 d-flex align-content-between w-100 flex-wrap text-white" style="background: #589C1C;">
                <div class="w-100">
                    <strong class="d-block mb-4">
                        Next Cycle Budget <br>
                        (Suggested)
                    </strong>
                </div>

                <?php
                if($CheckTotalNumberOfCyclesWithExpenses < 3) { ?>
                    <div id="col-lg-12 text-center mb-5" style="width: 100%;">
                        <p style="color: #ffff;">Sorry, you're not eligible to receive suggestions yet. Please complete at least 3 cycles with expenses to become eligible.</p>
                    </div>
                <?php } else { ?>


                    <div id="suggestions-container" style="width: 100%;">
                        <div class="w-100 text-white shadow text-center pt-3 pb-3 mt-3 mb-3 font-weight-bold" style="background: #E31A04; border-radius: 50px;">High Priority</div>
                        <div class="w-100 font-weight-bold" id="high_prio" style="text-align: center; font-size: 30px; display: flex; align-items: center; justify-content: center; width: 100%;">

                        </div>
                        <div style="display: flex; align-items: center; justify-content: center;">
                            <div id="high_prio_budget" styl></div>
                        </div>

                        <div class="w-100 text-white shadow text-center pt-3 pb-3 mt-3 mb-3 font-weight-bold" style="background: #FF7A00; border-radius: 50px;">Medium Priority</div>
                        <div class="w-100 font-weight-bold" id="med_prio" style="text-align: center; font-size: 30px; display: flex; align-items: center; justify-content: center; width: 100%;">

                        </div>
                        <div style="display: flex; align-items: center; justify-content: center;">
                            <div id="med_prio_budget"></div>
                        </div>

                        <div class="w-100 text-white shadow text-center pt-3 pb-3 mt-3 mb-3 font-weight-bold" style="background: #CFC700; border-radius: 50px;">Low Priority</div>
                        <div class="w-100 font-weight-bold" id="low_prio" style="text-align: center; font-size: 30px; display: flex; align-items: center; justify-content: center; width: 100%;">

                        </div>
                        <div style="display: flex; align-items: center; justify-content: center;">
                            <div id="low_prio_budget"></div>
                        </div>


                    </div>
                <?php } ?>
            </div>
        </div>
    </div>



</div>

<script>



    let is_customized = '<?=$suggestion_settings['is_customized'];?>' ?? '';
    // console.log("is_customized: " + is_customized);
    const toggleButton = document.getElementById('toggle');
    const selectElement = document.getElementById('no_of_cycles');
    const slider = toggleButton.querySelector('.slider');
    let isOn = false;

    const checkTotalNumberOfCyclesWithExpenses = '<?=$CheckTotalNumberOfCyclesWithExpenses; ?>';
    let parent_parent_id = '<?=$_SESSION['parent_parent_id'];?>';
    if (is_customized == 1) {
        isOn = true; // Set isOn to true to start in the "ON" state
        slider.textContent = 'ON';
        toggleButton.classList.add('on');
        toggleButton.classList.remove('off');
        slider.style.background = "#fff";
        selectElement.value = '<?=$suggestion_settings['num_of_cycle'];?>';
        selectElement.disabled = false;
    } else {
        // The default state should be "OFF"
        slider.textContent = 'OFF';
        toggleButton.classList.add('off');
        toggleButton.classList.remove('on');
        selectElement.value = "3";
        slider.style.background = "#D9D9D9";
        selectElement.disabled = true;
    }



    fetchSuggestions();
    if (checkTotalNumberOfCyclesWithExpenses > 2) {

        if(parent_parent_id == 0) {
            toggleButton.addEventListener('click', () => {
                isOn = !isOn;
                slider.textContent = isOn ? 'ON' : 'OFF';
                toggleButton.classList.toggle('on', isOn);
                toggleButton.classList.toggle('off', !isOn);
                selectElement.disabled = !isOn; // Enable or disable based on the slider state

                if (isOn) {
                    is_customized = 1; // Change is_customized to 0 when the toggle is turned ON
                } else {
                    is_customized = 0; // Change is_customized to 1 when the toggle is turned OFF
                }

                if (!isOn) {
                    selectElement.value = "3";
                    slider.style.background = "#D9D9D9";
                } else {
                    slider.style.background = "#fff";
                }


                fetchSuggestions();
            });
        } else {
            selectElement.value = '<?=$suggestion_settings['num_of_cycle'];?>';
            selectElement.disabled = true;
        }

        selectElement.addEventListener('change', () => {
            fetchSuggestions();
        });
    } else {
        slider.style.background = "#D9D9D9";
        selectElement.disabled = true;
    }
    function fetchSuggestions() {
        const isOn = !selectElement.disabled; // Check if the select is enabled
        const numberOfCycle = isOn ? selectElement.value : '3';
        $.ajax({
            type: 'POST',
            url: '/api/suggestions/SuggestionDataAjax.php',
            data: {
                parentId: '<?=$id;?>',
                numberOfCycle: numberOfCycle,
                is_customized: is_customized,
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    var suggestions = response.data;
                    var suggestionsContainer = $('#suggestions-container');

                    // Display percentages
                    $('#high_prio').html(response.data.high_percentage + '%');
                    $('#med_prio').html(response.data.medium_percentage + '%');
                    $('#low_prio').html(response.data.low_percentage + '%');

                    // Check if active_total_budget is not 0
                    if (parseFloat(response.data.active_total_budget) != 0) {
                        // Calculate and display allocated budgets
                        var totalBudget = parseFloat(response.data.active_total_budget);
                        // console.log("TOTAL BUDGET: " + totalBudget);
                        var highBudget = (parseFloat(response.data.high_percentage) / 100) * totalBudget;
                        var mediumBudget = (parseFloat(response.data.medium_percentage) / 100) * totalBudget;
                        var lowBudget = (parseFloat(response.data.low_percentage) / 100) * totalBudget;

                        $('#high_prio_budget').html("₱" + highBudget.toFixed(2) + " peso(s) of ₱" + totalBudget + " is your suggested budget based on your current cycle's budget on High Priority");
                        $('#med_prio_budget').html("₱" + mediumBudget.toFixed(2) + " peso(s) of ₱" + totalBudget + " is your suggested budget based on your current cycle's budget on Medium Priority");
                        $('#low_prio_budget').html("₱" + lowBudget.toFixed(2) + " peso(s) of ₱" + totalBudget + " is your suggested budget based on your current cycle's budget on Low Priority");

                    }
                } else {
                    $('#suggestions-container').html(response.message);
                }
            },

            error: function (xhr, status, error) {
                console.error('AJAX request error: ' + error);
            }
        });
    }




</script>
<?php require_once '../shared/footer.php'; ?>

