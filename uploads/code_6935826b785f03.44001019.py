
import numpy as np

# Load the NPZ file with allow_pickle=True for object arrays
file_path = '10000_subj_synthetic_cGAN_training_ready.npz'
data = np.load(file_path, allow_pickle=True)

print("=" * 60)
print("NPZ File Contents Overview")
print("=" * 60)

# List all arrays in the file
print("\nArrays in the file:")
print("-" * 60)
for key in data.files:
    print(f"  - {key}")

print("\n" + "=" * 60)
print("Detailed Information for Each Array")
print("=" * 60)

# Display detailed info for each array
for key in data.files:
    arr = data[key]
    print(f"\nArray name: {key}")
    print(f"  Shape: {arr.shape}")
    print(f"  Data type: {arr.dtype}")
    print(f"  Size: {arr.size} elements")
    print(f"  Memory: {arr.nbytes / (1024**2):.2f} MB")
    
    # Show basic statistics for numerical arrays
    if np.issubdtype(arr.dtype, np.number):
        print(f"  Min value: {np.min(arr)}")
        print(f"  Max value: {np.max(arr)}")
        print(f"  Mean value: {np.mean(arr):.4f}")
        print(f"  Std deviation: {np.std(arr):.4f}")
    
    if arr.ndim == 1:
        print(f"    {arr[:7]}")


# Close the file
data.close()

print("\n" + "=" * 60)
print("Done!")
print("=" * 60)